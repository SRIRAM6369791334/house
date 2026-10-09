@php
    /**
     * Reusable Instagram gallery partial.
     * Expects `$instagramImages` to be available in the parent view.
     */
    $instagramItems = collect($instagramImages ?? []);
    $instagramImageCount = $instagramItems->count();
    $instagramProfileUrl = optional($instagramItems->first())->link_url ?: '#';
@endphp

<style>
    .instagram-gallery-icon {
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none;
        height: 52px !important;
        width: 52px !important;
    }

    .instagram-gallery-item img,
    .instagram-gallery-item.is-muted img,
    .instagram-gallery-item.is-mono img {
        filter: brightness(1.04) contrast(1.03) saturate(1.08) !important;
        image-rendering: auto;
    }

    .instagram-gallery-icon svg {
        display: block;
        filter: drop-shadow(0 3px 7px rgba(0, 0, 0, .28));
        height: 48px;
        width: 48px;
    }

    .instagram-gallery-item:hover .instagram-gallery-icon,
    .instagram-gallery-item:focus .instagram-gallery-icon {
        background: transparent !important;
        transform: translate(-50%, -50%) scale(1.12) !important;
    }

    /* Instagram premium editorial showcase */
    .instagram-gallery-modern {
        background: #f3eee7 !important;
        overflow: hidden !important;
        /* padding: 96px 0 110px !important; */
        position: relative
    }

    /* .instagram-gallery-modern:before{color:rgba(15,15,15,.035);content:'SOCIAL';font-family:Arial,sans-serif;font-size:clamp(110px,17vw,280px);font-weight:900;letter-spacing:-.07em;line-height:1;pointer-events:none;position:absolute;right:-25px;top:22px} */
    .instagram-premium-head {
        align-items: flex-end;
        display: flex;
        justify-content: space-between;
        margin: 0 auto 48px;
        max-width: 1320px;
        padding: 0 24px;
        position: relative;
        z-index: 2
    }

    .instagram-premium-kicker {
        align-items: center;
        color: #d00000;
        display: flex;
        font-size: 10px;
        font-weight: 900;
        gap: 13px;
        letter-spacing: 3.2px;
        margin-bottom: 17px;
        text-transform: uppercase
    }

    .instagram-premium-kicker:before {
        background: #d00000;
        content: '';
        height: 1px;
        width: 38px
    }

    .instagram-premium-copy h2 {
        color: #111;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(40px, 4.5vw, 66px);
        letter-spacing: -.04em;
        line-height: .98;
        margin: 0;
    }

    .instagram-premium-copy h2 em {
        color: #d00000;
        font-family: Georgia, serif;
        font-weight: 400
    }

    .instagram-premium-action {
        padding-bottom: 5px
    }

    .instagram-premium-action p {
        color: #6d665f;
        font-size: 13px;
        line-height: 1.7;
        margin: 0 0 17px
    }

    .instagram-premium-action a {
        align-items: center;
        color: #111;
        display: inline-flex;
        font-size: 11px;
        font-weight: 900;
        gap: 14px;
        letter-spacing: 2px;
        text-decoration: none;
        text-transform: uppercase
    }

    .instagram-premium-action a span {
        color: #d00000;
        font-size: 18px;
        transition: transform .3s
    }

    .instagram-premium-action a:hover span {
        transform: translate(4px, -4px)
    }

    .instagram-gallery-modern .instagram-auto-slider {
        margin: 0 auto !important;
        max-width: 1368px !important;
        padding: 0 12px !important;
        position: relative;
        z-index: 2
    }

    .instagram-gallery-modern .slick-list {
        overflow: hidden !important;
        padding: 8px 0 28px !important
    }

    .instagram-gallery-modern .slick-track {
        align-items: stretch !important;
        display: flex !important
    }

    .instagram-gallery-modern .slick-slide {
        height: auto !important;
        padding: 0 10px !important
    }

    .instagram-gallery-modern .slick-slide>div {
        height: 100% !important
    }

    .instagram-gallery-modern .instagram-gallery-item {
        background: #111 !important;
        border-radius: 18px !important;
        box-shadow: 0 18px 45px rgba(38, 28, 19, .13) !important;
        display: block !important;
        height: 430px !important;
        overflow: hidden !important;
        position: relative !important;
        transform: translateY(0);
        transition: transform .5s cubic-bezier(.2, .75, .2, 1), box-shadow .5s !important
    }

    .instagram-gallery-modern .instagram-gallery-item:nth-child(even) {
        margin-top: 22px !important
    }

    .instagram-gallery-modern .instagram-gallery-item img,
    .instagram-gallery-modern .instagram-gallery-item.is-muted img,
    .instagram-gallery-modern .instagram-gallery-item.is-mono img {
        filter: none !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scale(1.01);
        transition: transform .8s cubic-bezier(.19, 1, .22, 1), filter .45s !important;
        width: 100% !important
    }

    .instagram-gallery-modern .instagram-gallery-item:after {
        background: linear-gradient(180deg, transparent 38%, rgba(0, 0, 0, .78));
        content: '';
        inset: 0;
        opacity: .55;
        position: absolute;
        transition: opacity .4s;
        z-index: 1
    }

    .instagram-gallery-modern .instagram-gallery-item:before {
        bottom: 25px;
        color: #fff;
        /* content: 'VIEW ON INSTAGRAM'; */
        font-size: 9px;
        font-weight: 900;
        left: 25px;
        letter-spacing: 2.1px;
        opacity: 0;
        position: absolute;
        transform: translateY(12px);
        transition: opacity .4s, transform .4s;
        z-index: 3
    }

    .instagram-gallery-modern .instagram-gallery-item:hover {
        box-shadow: 0 30px 65px rgba(38, 28, 19, .22) !important;
        transform: translateY(-10px)
    }

    .instagram-gallery-modern .instagram-gallery-item:hover img {
        filter: saturate(1.06) !important;
        transform: scale(1.08)
    }

    .instagram-gallery-modern .instagram-gallery-item:hover:after {
        opacity: 1
    }

    .instagram-gallery-modern .instagram-gallery-item:hover:before {
        opacity: 1;
        transform: none
    }

    .instagram-gallery-modern .instagram-gallery-icon {
        background: rgba(255, 255, 255, .94) !important;
        border-radius: 50% !important;
        height: 58px !important;
        left: 50% !important;
        opacity: 0;
        padding: 13px;
        position: absolute !important;
        top: 50% !important;
        transform: translate(-50%, -35%) scale(.8) !important;
        transition: opacity .4s, transform .45s !important;
        width: 58px !important;
        z-index: 4
    }

    .instagram-gallery-modern .instagram-gallery-icon svg {
        filter: none;
        height: 32px;
        width: 32px
    }

    .instagram-gallery-modern .instagram-gallery-item:hover .instagram-gallery-icon,
    .instagram-gallery-modern .instagram-gallery-item:focus .instagram-gallery-icon {
        background: rgba(255, 255, 255, .94) !important;
        opacity: 1;
        transform: translate(-50%, -50%) scale(1) !important
    }

    @media(max-width:991px) {
        .instagram-gallery-modern {
            padding: 76px 0 90px !important
        }

        .instagram-premium-head {
            align-items: flex-start
        }

        .instagram-gallery-modern .instagram-gallery-item {
            height: 390px !important
        }
    }

    @media(max-width:767px) {
        .instagram-premium-head {
            display: block;
            margin-bottom: 32px
        }

        .instagram-premium-action {
            margin-top: 24px
        }

        .instagram-premium-action p {
            display: none
        }

        .instagram-gallery-modern .instagram-gallery-item {
            height: 380px !important
        }

        .instagram-gallery-modern .instagram-gallery-item:nth-child(even) {
            margin-top: 0 !important
        }

        .instagram-gallery-modern:before {
            display: none
        }
    }

    /* Centered section title matching Video Testimonials */
    .instagram-premium-head {
        display: block !important;
        margin: 0 auto 55px !important;
        padding: 0 24px !important;
        text-align: center !important
    }

    .instagram-premium-kicker,
    .instagram-premium-action {
        display: none !important
    }

    .instagram-premium-copy h2 {
        color: #111 !important;
        font-family: 'Open Sans', Arial, sans-serif !important;
        font-size: clamp(42px, 5vw, 28px) !important;
        font-style: normal !important;
        font-weight: 800 !important;
        letter-spacing: -2px !important;
        line-height: 1.1 !important;
        margin: 0 !important
    }

    @media(max-width:767px) {
        .instagram-premium-head {
            margin-bottom: 36px !important
        }

        .instagram-premium-copy h2 {
            font-size: 38px !important;
            letter-spacing: -1.3px !important
        }
    }

    /* Instagram reference-style luxury title V3 */
    .instagram-gallery-modern {
        background: radial-gradient(circle at 50% 5%, #fffdf9 0, #f7f1e9 48%, #eee5d9 100%) !important;
        padding-top: 82px !important
    }

    .instagram-gallery-modern:before {
        background: radial-gradient(circle, rgba(190, 145, 73, .09) 1px, transparent 1.5px);
        background-size: 18px 18px;
        color: transparent !important;
        inset: 0;
        opacity: .28;
        top: 0
    }

    .instagram-premium-head {
        display: block !important;
        margin: 0 auto 62px !important;
        max-width: 1100px !important;
        padding: 0 24px !important;
        text-align: center !important
    }

    .instagram-premium-copy {
        position: relative
    }

    .instagram-title-icon {
        align-items: center;
        border: 1px solid #c99b4f;
        border-radius: 50%;
        color: #bd8c3d;
        display: flex;
        font-size: 25px;
        height: 54px;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        width: 54px;
        padding: 10px;
    }

    .instagram-title-icon:before,
    .instagram-title-icon:after {
        background: linear-gradient(90deg, transparent, #c99b4f);
        content: '';
        height: 1px;
        position: absolute;
        right: calc(100% + 14px);
        top: 50%;
        width: 150px
    }

    .instagram-title-icon:after {
        background: linear-gradient(90deg, #c99b4f, transparent);
        left: calc(100% + 14px);
        right: auto
    }

    .instagram-premium-kicker {
        color: #bd914a !important;
        display: block !important;
        font-family: 'Brush Script MT', 'Segoe Script', cursive !important;
        font-size: 35px !important;
        font-weight: 400 !important;
        letter-spacing: 0 !important;
        line-height: 1 !important;
        margin: 0 auto 8px !important;
        text-transform: none !important
    }

    .instagram-premium-kicker:before,
    .instagram-premium-kicker:after {
        display: none !important
    }

    .instagram-premium-copy h2 {
        color: #111 !important;
        font-family: 'Playfair Display', Georgia, serif !important;
        font-size: clamp(46px, 5.4vw, 72px) !important;
        font-weight: 600 !important;
        letter-spacing: -2.5px !important;
        line-height: 1.05 !important;
        margin: 0 !important
    }

    .instagram-premium-copy h2 span {
        background: linear-gradient(105deg, #a8782d, #e1bf75, #7c5940);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-style: normal
    }

    .instagram-premium-action {
        display: block !important;
        margin-top: 20px !important;
        padding: 0 !important;
        text-align: center !important
    }

    .instagram-premium-action p {
        color: #666 !important;
        display: block !important;
        font-family: 'Open Sans', Arial, sans-serif !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        margin: 0 auto 22px !important
    }

    .instagram-premium-action:after {
        color: #c49a56;
        content: '◆';
        display: block;
        font-size: 10px;
        letter-spacing: 0;
        margin: 0 auto;
        position: absolute;
        left: 0;
        right: 0;
        transform: translateY(-56px)
    }

    .instagram-premium-action a {
        background: rgba(255, 255, 255, .45);
        border: 1px solid #c99b4f;
        border-radius: 10px;
        color: #222 !important;
        font-size: 12px !important;
        gap: 10px !important;
        letter-spacing: .2px !important;
        margin-top: 10px;
        padding: 14px 26px;
        text-transform: none !important;
        transition: background .3s, box-shadow .3s, transform .3s
    }

    .instagram-premium-action a:before {
        color: #b38339;
        content: '\f16d';
        font-family: FontAwesome;
        font-size: 18px
    }

    .instagram-premium-action a:hover {
        background: #fff;
        box-shadow: 0 12px 30px rgba(151, 107, 46, .14);
        transform: translateY(-3px)
    }

    .instagram-premium-action a span {
        display: none
    }

    @media(max-width:767px) {
        .instagram-gallery-modern {
            padding-top: 65px !important
        }

        .instagram-premium-head {
            margin-bottom: 42px !important
        }

        .instagram-title-icon:before,
        .instagram-title-icon:after {
            width: 62px
        }

        .instagram-premium-kicker {
            font-size: 29px !important
        }

        .instagram-premium-copy h2 {
            font-size: 42px !important;
            letter-spacing: -1.6px !important
        }

        .instagram-premium-action p {
            font-size: 13px !important
        }

        .instagram-premium-action p br {
            display: none
        }
    }
</style>



<style>
    .instagram-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .insta-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 18px;
        border: 1px solid #c9a45c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c9a45c;
        font-size: 34px;
    }

    .insta-small-title {
        display: block;
        margin-bottom: 12px;
        font-family: "Georgia", serif;
        font-size: 30px;
        font-style: italic;
        color: #b89a5e;
    }

    .instagram-section h2 {
        margin: 0;
        font-size: 64px;
        font-weight: 700;
        line-height: 1.1;
        color: #111;
        font-family: "Playfair Display", serif;
    }

    .instagram-section p {
        max-width: 650px;
        margin: 22px auto 30px;
        font-size: 20px;
        line-height: 1.6;
        color: #666;
    }

    .instagram-section a {
        display: inline-block;
        padding: 14px 38px;
        border: 1px solid #c9a45c;
        border-radius: 14px;
        color: #111;
        font-size: 20px;
        font-weight: 600;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .instagram-section h2 {
            font-size: 38px;
        }

        .insta-small-title {
            font-size: 24px;
        }

        .instagram-section p {
            font-size: 16px;
        }
    }

    /* Instagram reference-style luxury title V3 */
    .instagram-gallery-modern {
        background: radial-gradient(circle at 50% 5%, #fffdf9 0, #f7f1e9 48%, #eee5d9 100%) !important;
        padding-top: 82px !important
    }

    .instagram-gallery-modern:before {
        background: radial-gradient(circle, rgba(190, 145, 73, .09) 1px, transparent 1.5px);
        background-size: 18px 18px;
        color: transparent !important;
        inset: 0;
        opacity: .28;
        top: 0
    }

    .instagram-premium-head {
        display: block !important;
        margin: 0 auto 62px !important;
        max-width: 1100px !important;
        padding: 0 24px !important;
        text-align: center !important
    }

    .instagram-premium-copy {
        position: relative
    }

    .instagram-title-icon {
        align-items: center;
        border: 1px solid #c99b4f;
        border-radius: 50%;
        color: #bd8c3d;
        display: flex;
        font-size: 25px;
        height: 54px;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        width: 54px
    }

    .instagram-title-icon:before,
    .instagram-title-icon:after {
        background: linear-gradient(90deg, transparent, #c99b4f);
        content: '';
        height: 1px;
        position: absolute;
        right: calc(100% + 14px);
        top: 50%;
        width: 150px
    }

    .instagram-title-icon:after {
        background: linear-gradient(90deg, #c99b4f, transparent);
        left: calc(100% + 14px);
        right: auto
    }

    .instagram-premium-kicker {
        color: #bd914a !important;
        display: block !important;
        font-family: 'Brush Script MT', 'Segoe Script', cursive !important;
        font-size: 35px !important;
        font-weight: 400 !important;
        letter-spacing: 0 !important;
        line-height: 1 !important;
        margin: 0 auto 8px !important;
        text-transform: none !important
    }

    .instagram-premium-kicker:before,
    .instagram-premium-kicker:after {
        display: none !important
    }

    .instagram-premium-copy h2 {
        color: #111 !important;
        font-family: 'Playfair Display', Georgia, serif !important;
        font-size: clamp(46px, 5.4vw, 72px) !important;
        font-weight: 600 !important;
        letter-spacing: -2.5px !important;
        line-height: 1.05 !important;
        margin: 0 !important
    }

    .instagram-premium-copy h2 span {
        background: linear-gradient(105deg, #a8782d, #e1bf75, #7c5940);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-style: normal
    }

    .instagram-premium-action {
        display: block !important;
        margin-top: 20px !important;
        padding: 0 !important;
        text-align: center !important
    }

    .instagram-premium-action p {
        color: #666 !important;
        display: block !important;
        font-family: 'Open Sans', Arial, sans-serif !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        margin: 0 auto 22px !important
    }

    .instagram-premium-action:after {
        color: #c49a56;
        content: '◆';
        display: block;
        font-size: 10px;
        letter-spacing: 0;
        margin: 0 auto;
        position: absolute;
        left: 0;
        right: 0;
        transform: translateY(-56px)
    }

    .instagram-premium-action a {
        background: rgba(255, 255, 255, .45);
        border: 1px solid #c99b4f;
        border-radius: 10px;
        color: #222 !important;
        font-size: 12px !important;
        gap: 10px !important;
        letter-spacing: .2px !important;
        margin-top: 10px;
        padding: 14px 26px;
        text-transform: none !important;
        transition: background .3s, box-shadow .3s, transform .3s
    }

    .instagram-premium-action a:before {
        color: #b38339;
        content: '\f16d';
        font-family: FontAwesome;
        font-size: 18px
    }

    .instagram-premium-action a:hover {
        background: #fff;
        box-shadow: 0 12px 30px rgba(151, 107, 46, .14);
        transform: translateY(-3px)
    }

    .instagram-premium-action a span {
        display: none
    }

    @media(max-width:767px) {
        .instagram-gallery-modern {
            padding-top: 65px !important
        }

        .instagram-premium-head {
            margin-bottom: 42px !important
        }

        .instagram-title-icon:before,
        .instagram-title-icon:after {
            width: 62px
        }

        .instagram-premium-kicker {
            font-size: 29px !important
        }

        .instagram-premium-copy h2 {
            font-size: 42px !important;
            letter-spacing: -1.6px !important
        }

        .instagram-premium-action p {
            font-size: 13px !important
        }

        .instagram-premium-action p br {
            display: none
        }
    }

    /* Final reference-matched Instagram header */
    .instagram-gallery-modern {
        /* background: linear-gradient(180deg, #fffdfa 0%, #cc0000 100%) !important; */

        /* background: linear-gradient(180deg, #fffdfa 0%, #f8f1e8 100%) !important; */
        padding-top: 45px !important
    }

    .instagram-gallery-modern:after {
        background: radial-gradient(ellipse at center, rgba(194, 151, 82, .10), transparent 68%);
        content: "";
        height: 420px;
        left: 50%;
        pointer-events: none;
        position: absolute;
        top: 0;
        transform: translateX(-50%);
        width: min(1100px, 100%)
    }

    .instagram-premium-head {
        margin-bottom: 62px !important;
        position: relative !important;
        z-index: 3 !important
    }

    .instagram-title-icon {
        background: rgba(255, 255, 255, .52) !important;
        border: 1.5px solid #c59a54 !important;
        color: #b8893f !important;
        font-family: FontAwesome !important;
        font-size: 27px !important;
        height: 64px !important;
        margin-bottom: 30px !important;
        width: 64px !important
    }

    .instagram-title-icon:before,
    .instagram-title-icon:after {
        width: 210px !important
    }

    .instagram-premium-kicker {
        color: #b99153 !important;
        font-family: "Segoe Script", "Brush Script MT", cursive !important;
        font-size: 42px !important;
        font-style: italic !important;
        line-height: 1.1 !important;
        margin-bottom: 10px !important
    }

    .instagram-premium-copy h2 {
        color: #111 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: clamp(52px, 5.7vw, 52px) !important;
        font-weight: 700 !important;
        letter-spacing: -3.5px !important;
        line-height: 1.02 !important
    }

    .instagram-premium-copy h2 span {
        background: linear-gradient(100deg, #b27f31 0%, #d4b06a 42%, #5a345c 100%) !important;
        -webkit-background-clip: text !important;
        background-clip: text !important;
        color: transparent !important
    }

    .instagram-premium-action {
        margin-top: 0 !important;
        position: relative !important
    }

    .instagram-premium-action p {
        color: #666 !important;
        font-size: 17px !important;
        line-height: 1.65 !important;
        margin-bottom: 68px !important
    }

    .instagram-premium-action:before {
        background: linear-gradient(90deg, transparent, #c69a52 25%, #c69a52 75%, transparent);
        content: "";
        height: 1px;
        left: 50%;
        position: absolute;
        top: 72px;
        transform: translateX(-50%);
        width: 250px
    }

    .instagram-premium-action:after {
        background: #f9f3eb;
        color: #c69a52 !important;
        font-size: 13px !important;
        line-height: 20px;
        padding: 0 12px;
        top: 63px !important;
        transform: none !important
    }

    .instagram-premium-action a {
        background: rgba(255, 255, 255, .58) !important;
        border: 1px solid #c99b4f !important;
        border-radius: 12px !important;
        font-size: 15px !important;
        padding: 16px 34px !important
    }

    .instagram-premium-action a:before {
        font-family: FontAwesome !important;
        font-size: 21px !important
    }

    .instagram-gallery-modern .instagram-gallery-icon {
        opacity: 1 !important;
        transform: translate(-50%, -50%) scale(.9) !important
    }

    .instagram-gallery-modern .instagram-gallery-item:hover .instagram-gallery-icon,
    .instagram-gallery-modern .instagram-gallery-item:focus .instagram-gallery-icon {
        transform: translate(-50%, -50%) scale(1) !important
    }

    @media(max-width:767px) {
        .instagram-gallery-modern {
            padding-top: 66px !important
        }

        .instagram-title-icon {
            height: 54px !important;
            width: 54px !important
        }

        .instagram-title-icon:before,
        .instagram-title-icon:after {
            width: 70px !important
        }

        .instagram-premium-kicker {
            font-size: 31px !important
        }

        .instagram-premium-copy h2 {
            font-size: 30px !important;
            letter-spacing: -2px !important
        }

        .instagram-premium-action p {
            font-size: 14px !important;
            margin-bottom: 58px !important
        }

        .instagram-premium-action:before {
            top: 67px;
            width: 190px
        }

        .instagram-premium-action:after {
            top: 58px !important
        }

        .instagram-premium-action a {
            font-size: 13px !important;
            padding: 13px 25px !important
        }
    }
    /* Mobile: keep two complete Instagram images visible in one row. */
    @media(max-width:479px) {
        .instagram-gallery-modern .instagram-auto-slider {
            padding: 0 6px !important;
        }

        .instagram-gallery-modern .slick-list {
            padding: 6px 0 18px !important;
        }

        .instagram-gallery-modern .slick-slide {
            padding: 0 4px !important;
        }

        .instagram-gallery-modern .instagram-gallery-item {
            border-radius: 10px !important;
            height: 220px !important;
        }

        .instagram-gallery-modern .instagram-gallery-icon {
            height: 42px !important;
            padding: 9px !important;
            width: 42px !important;
        }

        .instagram-gallery-modern .instagram-gallery-icon svg {
            height: 24px !important;
            width: 24px !important;
        }
    }
</style>
<section class="instagram-gallery-modern">
    <div class="instagram-premium-head">
        <div class="instagram-premium-copy">
            <span class="instagram-title-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none">
                        <defs>
                            <linearGradient id="instagram-gradient-5" x1="3" y1="21" x2="21" y2="3" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFB900"></stop>
                                <stop offset=".42" stop-color="#FF2D55"></stop>
                                <stop offset="1" stop-color="#8A3FFC"></stop>
                            </linearGradient>
                        </defs>
                        <rect x="3" y="3" width="18" height="18" rx="5" stroke="url(#instagram-gradient-5)" stroke-width="2.4"></rect>
                        <circle cx="12" cy="12" r="4.2" stroke="url(#instagram-gradient-5)" stroke-width="2.4"></circle>
                        <circle cx="17.4" cy="6.7" r="1.2" fill="#D62976"></circle>
                    </svg></span>

            {{-- <span class="instagram-premium-kicker">Stay Inspired</span> --}}
            <h2>Follow Us <span>on</span> Instagram</h2>
        </div>
        {{-- <div class="instagram-premium-action">
            <p>Be the first to see our latest collections, exclusive offers,<br>and style inspiration.</p>
            <a href="{{ $instagramProfileUrl }}" target="_blank" rel="noopener">@houseofknp <span>&nearr;</span></a>
        </div> --}}
    </div>
    <div class="instagram-auto-slider" data-slide-count="{{ $instagramImageCount > 0 ? $instagramImageCount : 3 }}">
        @forelse($instagramItems as $index => $instagramImage)
            @php
                $instagramImageFile = basename($instagramImage->bg_image);
                $instagramUrl = $instagramImage->link_url ?: '#';
            @endphp
            <a href="{{ $instagramUrl }}" class="instagram-gallery-item"
                aria-label="Open Instagram image {{ $index + 1 }}" target="_blank" rel="noopener">
                <img src="{{ route('instagram.image', ['filename' => $instagramImageFile]) }}"
                    alt="Instagram image {{ $index + 1 }}">
                <span class="instagram-gallery-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <defs>
                            <linearGradient id="instagram-gradient-{{ $index }}" x1="3" y1="21"
                                x2="21" y2="3" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFB900" />
                                <stop offset=".42" stop-color="#FF2D55" />
                                <stop offset="1" stop-color="#8A3FFC" />
                            </linearGradient>
                        </defs>
                        <rect x="3" y="3" width="18" height="18" rx="5"
                            stroke="url(#instagram-gradient-{{ $index }})" stroke-width="2.4" />
                        <circle cx="12" cy="12" r="4.2"
                            stroke="url(#instagram-gradient-{{ $index }})" stroke-width="2.4" />
                        <circle cx="17.4" cy="6.7" r="1.2" fill="#D62976" />
                    </svg>
                </span>
            </a>
        @empty
            <a href="#" class="instagram-gallery-item" aria-label="Instagram gallery image">
                <img src="{{ asset('images/knp/modern_hero.png') }}" alt="KNP lifestyle look">
                <span class="instagram-gallery-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <defs>
                            <linearGradient id="instagram-gradient-empty-1" x1="3" y1="21" x2="21"
                                y2="3" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFB900" />
                                <stop offset=".42" stop-color="#FF2D55" />
                                <stop offset="1" stop-color="#8A3FFC" />
                            </linearGradient>
                        </defs>
                        <rect x="3" y="3" width="18" height="18" rx="5"
                            stroke="url(#instagram-gradient-empty-1)" stroke-width="2.4" />
                        <circle cx="12" cy="12" r="4.2" stroke="url(#instagram-gradient-empty-1)"
                            stroke-width="2.4" />
                        <circle cx="17.4" cy="6.7" r="1.2" fill="#D62976" />
                    </svg>
                </span>
            </a>
            <a href="#" class="instagram-gallery-item" aria-label="Instagram gallery image">
                <img src="{{ asset('images/knp/lifestyle_banner.png') }}" alt="KNP street style look">
                <span class="instagram-gallery-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <defs>
                            <linearGradient id="instagram-gradient-empty-2" x1="3" y1="21" x2="21"
                                y2="3" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFB900" />
                                <stop offset=".42" stop-color="#FF2D55" />
                                <stop offset="1" stop-color="#8A3FFC" />
                            </linearGradient>
                        </defs>
                        <rect x="3" y="3" width="18" height="18" rx="5"
                            stroke="url(#instagram-gradient-empty-2)" stroke-width="2.4" />
                        <circle cx="12" cy="12" r="4.2" stroke="url(#instagram-gradient-empty-2)"
                            stroke-width="2.4" />
                        <circle cx="17.4" cy="6.7" r="1.2" fill="#D62976" />
                    </svg>
                </span>
            </a>
            <a href="#" class="instagram-gallery-item" aria-label="Instagram gallery image">
                <img src="{{ asset('images/knp/brand_story_new.png') }}" alt="KNP brand story look">
                <span class="instagram-gallery-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <defs>
                            <linearGradient id="instagram-gradient-empty-3" x1="3" y1="21"
                                x2="21" y2="3" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFB900" />
                                <stop offset=".42" stop-color="#FF2D55" />
                                <stop offset="1" stop-color="#8A3FFC" />
                            </linearGradient>
                        </defs>
                        <rect x="3" y="3" width="18" height="18" rx="5"
                            stroke="url(#instagram-gradient-empty-3)" stroke-width="2.4" />
                        <circle cx="12" cy="12" r="4.2" stroke="url(#instagram-gradient-empty-3)"
                            stroke-width="2.4" />
                        <circle cx="17.4" cy="6.7" r="1.2" fill="#D62976" />
                    </svg>
                </span>
            </a>
        @endforelse
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.jQuery || !jQuery.fn.slick) {
            return;
        }

        jQuery('.instagram-auto-slider').each(function() {
            const $instagramSlider = jQuery(this);

            if ($instagramSlider.hasClass('slick-initialized')) {
                return;
            }

            const slideCount = $instagramSlider.children('.instagram-gallery-item').length;
            const visibleSlides = Math.min(5, slideCount);

            if (!slideCount) {
                return;
            }

            $instagramSlider.slick({
                slidesToShow: visibleSlides,
                slidesToScroll: 1,
                autoplay: slideCount > visibleSlides,
                autoplaySpeed: 0,
                speed: 4500,
                cssEase: 'linear',
                infinite: slideCount > visibleSlides,
                arrows: false,
                dots: false,
                pauseOnHover: false,
                pauseOnFocus: false,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: Math.min(4, slideCount)
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: Math.min(3, slideCount)
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: Math.min(2, slideCount)
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: Math.min(2, slideCount)
                        }
                    }
                ]
            });
        });
    });
</script>


