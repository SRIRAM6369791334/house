const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

// Execute the actual Blade inline script with only server expressions replaced.
const template = fs.readFileSync(path.join(__dirname, '../resources/views/pages/checkout.blade.php'), 'utf8');
const script = template.match(/<script>([\s\S]*?)<\/script>/)[1]
    .replace(/@json\(auth\(\)->check\(\)\)/g, 'true')
    .replace(/@json\(\(\$savedCheckoutAddresses[^\n]+\)/g, '[]')
    .replace(/@json\(route\('([^']+)'[^\n]*?\)\)/g, (_, name) => JSON.stringify(name))
    .replace(/@json\(url\('([^']+)'\)\)/g, (_, name) => JSON.stringify('/' + name));

function browser(verificationResponses) {
    let submit;
    let openCount = 0;
    const requests = [];
    const button = { disabled: false, style: {}, textContent: '' };
    const error = { style: {}, textContent: '' };
    const form = {
        action: '/checkout',
        addEventListener: (event, callback) => { if (event === 'submit') submit = callback; },
        querySelectorAll: () => [],
        querySelector: () => ({ value: 'csrf-token' }),
    };
    const window = { location: { href: '' } };
    const context = {
        document: {
            addEventListener: (_, callback) => callback(),
            getElementById: id => ({ checkoutPlaceForm: form, checkoutPayButton: button, checkoutPaymentError: error }[id] ?? null),
            querySelectorAll: () => [],
            querySelector: () => null,
        },
        window,
        FormData: class {},
        Cashfree: () => ({ checkout: async () => { openCount++; return { paymentDetails: {} }; } }),
        setTimeout: callback => callback(),
        clearTimeout: () => {},
        alert: () => {},
        fetch: async (url, options) => {
            requests.push({ url, options });
            if (url === '/checkout') {
                return { ok: true, status: 200, json: async () => ({ payment_session_id: 'session', order_number: 'ORDER-1' }) };
            }
            assert.equal(url, 'checkout.cashfree.verify');
            assert.equal(JSON.parse(options.body).order_number, 'ORDER-1');
            const response = verificationResponses.shift();
            assert.ok(response, 'Unexpected extra verification request');
            if (response instanceof Error) throw response;
            return { ok: response.status < 400, status: response.status, json: async () => response.body };
        },
    };
    vm.runInNewContext(script, context);
    assert.equal(typeof submit, 'function', 'Checkout script must initialize its Pay handler without ReferenceError');
    return {
        submit: () => submit.call(form, { preventDefault() {} }),
        button, error, window, requests, openCount: () => openCount,
    };
}

const pending = () => ({ status: 202, body: { pending: true, message: 'Still pending. Do not pay again.' } });
const success = () => ({ status: 200, body: { success: true, message: 'Payment successful.', thankyou_url: '/thankyou' } });

test('Pay handler initializes and waits for delayed confirmation before redirecting', async () => {
    const page = browser([pending(), pending(), success()]);
    await page.submit();
    assert.equal(page.window.location.href, '/thankyou');
    assert.equal(page.openCount(), 1);
    assert.equal(page.requests.length, 4);
});

test('Retry confirms the same order without creating another order or charging again', async () => {
    const page = browser([...Array.from({ length: 6 }, pending), success()]);
    await page.submit();
    assert.equal(page.window.location.href, '');
    assert.equal(page.button.textContent, 'Retry Payment Confirmation');
    assert.equal(page.button.disabled, false);
    await page.submit();
    assert.equal(page.window.location.href, '/thankyou');
    assert.equal(page.requests.filter(request => request.url === '/checkout').length, 1);
    assert.equal(page.openCount(), 1);
});

test('Network error after payment preserves the order for confirmation retry', async () => {
    const page = browser([new Error('Network unavailable'), success()]);
    await page.submit();
    assert.equal(page.button.textContent, 'Retry Payment Confirmation');
    await page.submit();
    assert.equal(page.window.location.href, '/thankyou');
    assert.equal(page.openCount(), 1);
});
