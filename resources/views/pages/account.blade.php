@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

<style>
.account-modern-page {
    background: #FAFAF9;
    padding: 80px 0 120px;
    font-family: 'Manrope', sans-serif;
    color: #1C1917;
}

.account-shell {
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    gap: 40px;
    max-width: 1300px;
    margin: 0 auto;
}

.account-sidebar,
.account-main {
    background: #FFFFFF;
    border: 1px solid #EAEAEA;
    box-shadow: 0 4px 24px rgba(0,0,0,0.02);
}

.account-sidebar {
    overflow: hidden;
    height: fit-content;
}

.account-user-mini {
    padding: 40px 24px 30px;
    text-align: center;
}

.account-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1C1917, #44403C);
    color: #A16207;
    font-size: 36px;
    font-weight: 300;
    font-family: 'Cormorant Garamond', serif;
    border: 4px solid #FAFAF9;
    box-shadow: 0 8px 16px rgba(0,0,0,0.05);
}

.account-user-mini h3,
.account-profile-head h1 {
    margin: 0;
    color: #1C1917;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    letter-spacing: 0.5px;
}

.account-user-mini p,
.account-profile-head p {
    margin: 8px 0 0;
    color: #64748B;
    font-size: 13px;
    letter-spacing: 1px;
    word-break: break-word;
    overflow-wrap: break-word;
}

.account-menu-title {
    background: #FAFAF9;
    padding: 24px 30px;
    font-size: 11px;
    font-weight: 800;
    color: #1C1917;
    text-transform: uppercase;
    letter-spacing: 3px;
    border-top: 1px solid #EAEAEA;
    border-bottom: 1px solid #EAEAEA;
}

.account-nav {
    list-style: none;
    margin: 0;
    padding: 20px 0;
}

.account-nav li {
    display: block;
    margin: 0;
}

.account-nav a,
.account-nav button {
    display: block;
    width: 100%;
    color: #44403C;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-decoration: none;
    background: none;
    border: 0;
    padding: 18px 30px;
    text-align: left;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.account-nav .active,
.account-nav a:hover,
.account-nav button:hover {
    color: #A16207;
    background: #FAFAF9;
    border-left-color: #A16207;
}

.account-profile-head {
    display: grid;
    grid-template-columns: 100px minmax(0, 1fr) auto;
    gap: 30px;
    align-items: center;
    padding: 40px;
    border-bottom: 1px solid #EAEAEA;
    background: #FFFFFF;
}

.account-profile-head .account-avatar {
    margin: 0;
}

.edit-profile-btn {
    border: 1px solid #D6D3D1;
    color: #1C1917;
    background: #FFFFFF;
    padding: 12px 28px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.edit-profile-btn:hover {
    background: #1C1917;
    color: #FFFFFF;
    border-color: #1C1917;
}

.account-detail-body {
    padding: 40px;
}

.account-field-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 30px;
}

.account-field.full {
    grid-column: 1 / -1;
}

.account-field label {
    color: #64748B;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
    display: block;
}

.account-field .readonly-value {
    min-height: 54px;
    border-bottom: 1px solid #EAEAEA;
    background: transparent;
    color: #1C1917;
    padding: 14px 0;
    font-size: 16px;
    font-family: 'Playfair Display', serif;
    word-break: break-word;
    overflow-wrap: break-word;
}

.account-field input,
.account-field select,
.account-field textarea {
    width: 100%;
    min-height: 54px;
    border: 1px solid #EAEAEA;
    border-radius: 0;
    background: #FAFAF9;
    color: #1C1917;
    padding: 14px 20px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.account-field textarea {
    min-height: 120px;
    resize: vertical;
}

.account-field input:focus,
.account-field select:focus,
.account-field textarea:focus {
    border-color: #A16207;
    background: #FFFFFF;
    outline: none;
    box-shadow: 0 0 0 1px #A16207;
}

.password-field-wrap { position: relative; }
.password-field-wrap input { padding-right: 50px; }
.password-eye-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #A16207;
    cursor: pointer;
    font-size: 16px;
}

.account-actions {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 40px;
}

.account-outline-btn,
.account-muted-btn,
.account-save-btn,
.account-danger-btn {
    padding: 14px 32px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-block;
}

.account-outline-btn {
    border: 1px solid #1C1917;
    background: transparent;
    color: #1C1917;
}
.account-save-btn {
    border: 1px solid #1C1917;
    background: #1C1917;
    color: #FFFFFF;
}
.account-muted-btn {
    border: 1px solid #D6D3D1;
    background: #FAFAF9;
    color: #64748B;
}
.account-danger-btn {
    border: 1px solid #DC2626;
    background: #DC2626;
    color: #FFFFFF;
}

.account-outline-btn:hover { background: #1C1917; color: #FFFFFF; }
.account-save-btn:hover { background: #A16207; border-color: #A16207; color: #FFFFFF; }
.account-muted-btn:hover { background: #1C1917; color: #FFFFFF; border-color: #1C1917; }
.account-danger-btn:hover { background: #B91C1C; }

.account-section-head {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
    margin-bottom: 30px;
}

.account-section-head h2 {
    margin: 0;
    color: #1C1917;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    font-size: 24px;
}

.account-address-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.account-address-card {
    border: 1px solid #EAEAEA;
    background: #FFFFFF;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}
.account-address-card:hover {
    border-color: #D6D3D1;
    box-shadow: 0 8px 24px rgba(0,0,0,0.03);
    transform: translateY(-2px);
}

.account-address-card.default {
    border-color: #A16207;
}

.account-address-body {
    padding: 24px;
    flex-grow: 1;
}

.account-address-body h3 {
    margin: 0 0 12px;
    color: #1C1917;
    font-size: 16px;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
}

.account-address-body p {
    margin: 0;
    color: #44403C;
    line-height: 1.6;
    font-size: 14px;
    word-break: break-word;
    overflow-wrap: break-word;
}

.account-address-actions {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
    border-top: 1px solid #EAEAEA;
    padding: 16px 24px;
    background: #FAFAF9;
}

.account-address-actions button {
    border: 0;
    background: transparent;
    color: #64748B;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0;
    transition: color 0.3s ease;
}

.account-address-actions button:hover {
    color: #A16207;
}

.account-default-badge {
    display: inline-flex;
    background: #A16207;
    color: #FFFFFF;
    padding: 6px 12px;
    font-size: 10px;
    font-weight: 800;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.account-empty-box {
    border: 1px dashed #D6D3D1;
    padding: 40px;
    color: #64748B;
    text-align: center;
    font-style: italic;
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    background: #FAFAF9;
}

/* LUXURY ORDER CARDS */
.luxury-orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
}
.luxury-order-card {
    background: #FFFFFF;
    border: 1px solid #EAEAEA;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    transition: all 0.3s ease;
}
.luxury-order-card:hover {
    border-color: #A16207;
    box-shadow: 0 8px 24px rgba(161,98,7,0.06);
    transform: translateY(-2px);
}
.loc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #EAEAEA;
    padding-bottom: 16px;
}
.loc-id {
    font-size: 11px;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 2px;
}
.loc-id span {
    color: #1C1917;
    font-weight: 800;
}
.loc-status {
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 6px 12px;
    background: #FAFAF9;
    color: #64748B;
}
.loc-status.status-pending { background: #FEF3C7; color: #92400E; }
.loc-status.status-processing { background: #DBEAFE; color: #1E40AF; }
.loc-status.status-completed { background: #D1FAE5; color: #065F46; }
.loc-status.status-cancelled { background: #FEE2E2; color: #991B1B; }

.loc-body {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.loc-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.loc-label {
    font-size: 11px;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.loc-value {
    font-size: 15px;
    font-weight: 600;
    color: #1C1917;
    font-family: 'Playfair Display', serif;
}

.loc-footer {
    margin-top: auto;
    padding-top: 16px;
}
.loc-view-btn {
    width: 100%;
    background: transparent;
    border: 1px solid #1C1917;
    color: #1C1917;
    padding: 14px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    cursor: pointer;
    transition: all 0.3s;
}
.loc-view-btn:hover {
    background: #1C1917;
    color: #FFFFFF;
}

.account-order-detail-head {
    display: flex;
    gap: 24px;
    align-items: center;
    background: #FAFAF9;
    padding: 30px;
    border: 1px solid #EAEAEA;
    margin-bottom: 30px;
}

.account-order-thumb {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 1px solid #EAEAEA;
}

.account-order-detail-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.account-detail-label {
    font-size: 10px;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 6px;
    display: block;
}

.account-detail-value {
    font-size: 16px;
    color: #1C1917;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    word-break: break-word;
    overflow-wrap: break-word;
}

.account-order-tabs {
    display: flex;
    gap: 12px;
    border-bottom: 1px solid #EAEAEA;
    margin-bottom: 30px;
    overflow-x: auto;
}

.account-order-tabs button {
    border: 0;
    background: transparent;
    padding: 16px 24px;
    font-size: 11px;
    font-weight: 800;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 2px;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.account-order-tabs button:hover {
    color: #1C1917;
}

.account-order-tabs button.active {
    color: #A16207;
    border-bottom-color: #A16207;
}

.account-order-tab-panel {
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

.account-order-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 20px;
    align-items: center;
    padding: 20px;
    border: 1px solid #EAEAEA;
    margin-bottom: 12px;
    background: #FFFFFF;
    transition: border-color 0.3s ease;
}
.account-order-item:hover {
    border-color: #D6D3D1;
}

.d-none { display: none !important; }

@media (max-width: 991px) {
    .account-modern-page { padding: 40px 0 60px; }
    .account-shell { grid-template-columns: minmax(0, 1fr); gap: 24px; }
    .account-field-grid, .account-address-grid { grid-template-columns: minmax(0, 1fr); }
}

/* PATCH FOR BUTTONS AND CHECKBOXES */
.account-address-actions form {
    margin: 0;
}
.account-address-actions form button[type="submit"] {
    border: 0 !important;
    background: transparent !important;
    color: #64748B !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 0 !important;
    transition: color 0.3s ease !important;
    min-height: auto !important;
    height: auto !important;
    border-radius: 0 !important;
}
.account-address-actions form button[type="submit"]:hover {
    color: #DC2626 !important; /* Make delete/default actions red on hover */
}

/* Fix giant checkboxes */
.account-field input[type="checkbox"],
.account-field input[type="radio"] {
    width: auto !important;
    min-height: 0 !important;
    height: 18px !important;
    width: 18px !important;
    padding: 0 !important;
    margin: 0 10px 0 0 !important;
    vertical-align: middle !important;
    cursor: pointer;
}

.account-checkbox-label,
.account-check {
    display: flex !important;
    align-items: center !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1C1917 !important;
    text-transform: none !important;
    letter-spacing: 0 !important;
    cursor: pointer;
}

/* PATCH FOR ORDER DETAILS */
.account-order-detail-head {
    background: #FFFFFF !important;
    border: none !important;
    border-bottom: 1px solid #EAEAEA !important;
    padding: 0 0 30px 0 !important;
    margin-bottom: 30px !important;
}

.account-order-detail-head h2 {
    font-size: 24px !important;
    letter-spacing: 1px !important;
    margin-top: 10px !important;
}

.account-order-thumb {
    border-radius: 8px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
    border: none !important;
    width: 80px !important;
    height: 80px !important;
}

.account-status-badge {
    font-size: 9px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 2px !important;
    padding: 6px 12px !important;
    background: #FAFAF9 !important;
    color: #64748B !important;
    border-radius: 3px !important;
    display: inline-block !important;
}

.account-order-tabs {
    border-bottom: 2px solid #EAEAEA !important;
}

.account-order-tabs button {
    border-bottom: 2px solid transparent !important;
    margin-bottom: -2px !important;
    padding: 12px 20px !important;
}

.account-order-tabs button.active {
    color: #1C1917 !important;
    border-bottom-color: #1C1917 !important;
}

/* SPECIFICITY OVERRIDES TO BEAT GLOBAL RED BUTTONS */
body .account-shell form button[type="submit"].account-save-btn,
body .account-shell button[type="submit"].account-save-btn {
    background: #1C1917 !important;
    border: 1px solid #1C1917 !important;
    color: #FFFFFF !important;
    border-radius: 0 !important;
}

body .account-shell form button[type="submit"].account-save-btn:hover,
body .account-shell button[type="submit"].account-save-btn:hover {
    background: #A16207 !important;
    border-color: #A16207 !important;
}

body .account-shell form button[type="submit"] {
    background: transparent !important;
    color: #64748B !important;
}
body .account-shell form button[type="submit"]:hover {
    color: #DC2626 !important;
}
/* Except update button */
body .account-shell form button[type="submit"].account-save-btn {
    background: #1C1917 !important;
    color: #FFFFFF !important;
}

/* FIX CHECKBOX EXPLICITLY */
body .account-shell .account-check input[type="checkbox"],
body .account-shell label.account-check input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    min-height: 18px !important;
    margin: 0 10px 0 0 !important;
    padding: 0 !important;
    appearance: auto !important;
    -webkit-appearance: checkbox !important;
    border: 1px solid #1C1917 !important;
}

@media (max-width: 767px) {
    /* Hide floating scroll-up button on mobile to prevent overlapping */
    #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
        display: none !important;
    }
}

/* MASTER FIX FOR ADDRESS FORM BUTTONS */
body .account-shell .account-address-actions {
    display: flex !important;
    gap: 20px !important;
    align-items: center !important;
    border-top: 1px solid #EAEAEA !important;
    padding: 16px 24px !important;
    background: #FAFAF9 !important;
}

body .account-shell .account-address-actions form {
    margin: 0 !important;
    padding: 0 !important;
}

body .account-shell .account-address-actions button,
body .account-shell .account-address-actions form button[type="submit"] {
    border: 0 !important;
    background: transparent !important;
    color: #64748B !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 0 !important;
    transition: color 0.3s ease !important;
    min-height: auto !important;
    height: auto !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    line-height: 1 !important;
}

body .account-shell .account-address-actions button:hover,
body .account-shell .account-address-actions form button[type="submit"]:hover {
    color: #DC2626 !important;
}

/* FIX ADDRESS FORM LAYOUT (LABEL AND CHECKBOX ALIGNMENT) */
body .account-shell .account-check {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1C1917 !important;
    margin-top: 20px !important;
    margin-bottom: 0 !important;
    width: fit-content !important;
    cursor: pointer !important;
}

body .account-shell label.account-check input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    min-width: 18px !important;
    min-height: 18px !important;
    margin: 0 10px 0 0 !important;
    padding: 0 !important;
    appearance: auto !important;
    -webkit-appearance: checkbox !important;
    box-shadow: none !important;
}
/* ==========================================================================
   AUTHORITATIVE MOBILE & TABLET RESPONSIVENESS OVERHAUL (HOUSE OF KNP ATELIER)
   ========================================================================== */
@media (max-width: 991px) {
    .account-modern-page {
        padding: 24px 0 60px !important;
    }
    .account-shell {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 18px !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    .account-sidebar,
    .account-main {
        min-width: 0 !important;
        border-radius: 10px !important;
        border: 1px solid #e7e2db !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04) !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }

    /* Top User Profile Summary Card */
    .account-user-mini {
        padding: 22px 16px 18px !important;
        text-align: center !important;
    }
    .account-avatar {
        width: 76px !important;
        height: 76px !important;
        font-size: 28px !important;
        margin: 0 auto 12px !important;
    }
    .account-user-mini h3 {
        font-size: 19px !important;
    }
    .account-user-mini p {
        font-size: 12.5px !important;
        margin-top: 4px !important;
    }
    .account-menu-title {
        display: none !important;
    }

    /* Horizontal Touch-Scrollable Tab Pills */
    .account-nav {
        display: flex !important;
        flex-direction: row !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
        gap: 8px !important;
        padding: 12px 14px !important;
        border-top: 1px solid #f0ebe4 !important;
        background: #faf9f7 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .account-nav::-webkit-scrollbar,
    .account-nav::-webkit-scrollbar-thumb,
    .account-nav::-webkit-scrollbar-track,
    .account-nav::-webkit-scrollbar-button {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        background: transparent !important;
    }
    .account-nav li {
        flex: 0 0 auto !important;
        display: inline-block !important;
    }
    .account-nav a,
    .account-nav button {
        padding: 9px 16px !important;
        font-size: 11px !important;
        letter-spacing: 1.2px !important;
        border-radius: 6px !important;
        border: 1px solid #e2ded8 !important;
        background: #ffffff !important;
        color: #44403c !important;
        white-space: nowrap !important;
        text-align: center !important;
        border-left: 1px solid #e2ded8 !important;
        transition: all 0.25s ease !important;
        display: inline-block !important;
        width: auto !important;
    }
    .account-nav a.active,
    .account-nav button.active {
        background: #CC0000 !important;
        color: #ffffff !important;
        border-color: #CC0000 !important;
        border-left-color: #CC0000 !important;
        box-shadow: 0 3px 10px rgba(204, 0, 0, 0.25) !important;
    }

    /* Streamline Main Profile Header (Eliminate duplicate avatar on mobile) */
    .account-profile-head {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 14px 18px !important;
        background: #faf9f7 !important;
        border-bottom: 1px solid #f0ebe4 !important;
    }
    .account-profile-head:has(.edit-profile-btn.d-none) {
        display: none !important;
    }
    .account-profile-head .account-avatar {
        display: none !important;
    }
    .account-profile-head h1 {
        font-size: 17px !important;
        margin: 0 !important;
    }
    .account-profile-head p {
        display: none !important;
    }
    .edit-profile-btn {
        margin: 0 !important;
        padding: 8px 16px !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        border-radius: 6px !important;
        border-color: #CC0000 !important;
        color: #CC0000 !important;
        background: #ffffff !important;
    }
    .edit-profile-btn:hover {
        background: #CC0000 !important;
        color: #ffffff !important;
    }

    .account-detail-body {
        padding: 20px 16px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .account-field-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
        width: 100% !important;
    }
    .account-field {
        width: 100% !important;
        min-width: 0 !important;
    }
    .account-field input,
    .account-field select,
    .account-field textarea {
        min-height: 46px !important;
        padding: 12px 14px !important;
        font-size: 13.5px !important;
        border-radius: 6px !important;
        background: #fafbfc !important;
        border: 1px solid #e2ded8 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .account-field textarea {
        min-height: 80px !important;
    }
    .account-field input:focus,
    .account-field select:focus,
    .account-field textarea:focus {
        border-color: #CC0000 !important;
        box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08) !important;
    }
    .account-field .readonly-value {
        min-height: 42px !important;
        padding: 10px 0 !important;
        font-size: 14.5px !important;
        word-break: break-word !important;
    }
    .account-actions {
        display: flex !important;
        flex-direction: row !important;
        gap: 10px !important;
        margin-top: 20px !important;
        width: 100% !important;
    }
    .account-actions button {
        flex: 1 1 0 !important;
        border-radius: 6px !important;
        padding: 12px 18px !important;
        font-size: 10.5px !important;
        letter-spacing: 1.2px !important;
        min-height: 44px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
    }
    .account-actions.js-default-actions .account-outline-btn {
        width: 100% !important;
        flex: 1 1 auto !important;
    }

    /* Section Head (Addresses, Orders) */
    .account-section-head {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 12px !important;
        margin-bottom: 18px !important;
    }
    .account-section-head h2 {
        font-size: 19px !important;
        margin: 0 !important;
    }
    .account-section-head .account-save-btn {
        padding: 9px 18px !important;
        min-height: 38px !important;
        font-size: 10px !important;
        letter-spacing: 1.2px !important;
        background: #CC0000 !important;
        border-color: #CC0000 !important;
        color: #fff !important;
        border-radius: 6px !important;
    }

    /* Address Grid & Cards */
    .account-address-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
        width: 100% !important;
    }
    .account-address-card {
        border-radius: 8px !important;
        border: 1px solid #e7e2db !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .account-address-card.default {
        border-color: #CC0000 !important;
    }
    .account-default-badge {
        background: #CC0000 !important;
        border-radius: 4px !important;
        font-size: 9px !important;
        padding: 4px 10px !important;
        letter-spacing: 1.5px !important;
    }
    .account-address-body {
        padding: 16px 14px !important;
    }
    .account-address-actions {
        padding: 12px 14px !important;
        gap: 14px !important;
        background: #faf9f7 !important;
        border-radius: 0 0 8px 8px !important;
    }

    /* Orders Grid & Cards */
    .luxury-orders-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
        width: 100% !important;
    }
    .luxury-order-card {
        border-radius: 10px !important;
        border: 1px solid #e7e2db !important;
        padding: 16px 14px !important;
        gap: 12px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .loc-status {
        border-radius: 20px !important;
        padding: 4px 10px !important;
        font-size: 8.5px !important;
    }
    .loc-view-btn {
        border-radius: 6px !important;
        padding: 12px !important;
        font-size: 10.5px !important;
        border-color: #CC0000 !important;
        color: #CC0000 !important;
        background: #ffffff !important;
    }
    .loc-view-btn:hover {
        background: #CC0000 !important;
        color: #ffffff !important;
    }

    /* Order Detail View Overhaul (Fixes horizontal blowout seen in screenshot) */
    .account-order-detail {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .account-order-detail-head {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        text-align: left !important;
        padding: 16px 14px !important;
        gap: 12px !important;
        background: #faf9f7 !important;
        border-radius: 8px !important;
        border: 1px solid #e7e2db !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 20px !important;
    }
    .account-order-detail-head > div {
        width: 100% !important;
        min-width: 0 !important;
    }
    .account-order-detail-head h2 {
        font-size: clamp(14px, 4.4vw, 17px) !important;
        line-height: 1.3 !important;
        word-break: break-all !important;
        overflow-wrap: anywhere !important;
        margin: 6px 0 0 !important;
        letter-spacing: 0.5px !important;
        color: #1C1917 !important;
    }
    .account-order-thumb {
        width: 60px !important;
        height: 60px !important;
        border-radius: 6px !important;
        object-fit: cover !important;
    }
    .account-status-badge {
        font-size: 8.5px !important;
        padding: 4px 10px !important;
        border-radius: 20px !important;
        letter-spacing: 1.5px !important;
    }
    .account-order-detail-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 14px 12px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 22px !important;
    }
    .account-order-detail-grid > div {
        min-width: 0 !important;
        width: 100% !important;
    }
    .account-order-detail-grid > div:last-child {
        grid-column: 1 / -1 !important;
    }
    .account-detail-label {
        font-size: 9.5px !important;
        letter-spacing: 1.5px !important;
        margin-bottom: 4px !important;
    }
    .account-detail-value {
        font-size: 13.5px !important;
        line-height: 1.45 !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        color: #1C1917 !important;
    }
    .account-order-tabs {
        display: flex !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
        gap: 6px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border-bottom: 2px solid #eaeaea !important;
        margin-bottom: 20px !important;
    }
    .account-order-tabs::-webkit-scrollbar,
    .account-order-tabs::-webkit-scrollbar-thumb,
    .account-order-tabs::-webkit-scrollbar-track,
    .account-order-tabs::-webkit-scrollbar-button {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        background: transparent !important;
    }
    .account-order-tabs button {
        flex: 0 0 auto !important;
        padding: 10px 14px !important;
        font-size: 10px !important;
        letter-spacing: 1px !important;
        white-space: nowrap !important;
    }
    .account-order-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        padding: 12px 14px !important;
        gap: 6px !important;
        border-radius: 6px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .account-order-item .account-detail-value {
        font-size: 14px !important;
        font-weight: 700 !important;
    }

    /* Hide Back-to-Top Button on Mobile */
    #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
        display: none !important;
    }
}
</style>

<section class="account-modern-page">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="account-shell">
            <aside class="account-sidebar">
                <div class="account-user-mini">
                    <div class="account-avatar">{{ $initial }}</div>
                    <h3>{{ $user->name }}</h3>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="account-menu-title">My Account</div>
                <ul class="account-nav">
                    <li><a class="active js-account-tab" href="#profile" data-account-target="profile">Profile</a></li>
                    <li><a class="js-account-tab" href="#address" data-account-target="address">Address</a></li>
                    <li><a class="js-account-tab" href="#orders" data-account-target="orders">Orders</a></li>
                    <li><a href="{{ url('wishlist') }}">Wishlist</a></li>
                    <li><a href="{{ url('cart') }}">Cart</a></li>
                </ul>
            </aside>

            <main class="account-main">
                <div class="account-profile-head">
                    <div class="account-avatar">{{ $initial }}</div>
                    <div>
                        <h1>{{ $user->name }}</h1>
                        <p>{{ $user->email }}</p>
                    </div>
                    <button type="button" class="edit-profile-btn js-edit-profile"><i class="fa fa-pencil"></i> Edit</button>
                </div>

                <div class="account-detail-body">
                    <div class="account-panel js-account-panel" data-account-panel="profile">
                        <div class="account-field-grid js-profile-view">
                            <div class="account-field full">
                                <label>Full Name</label>
                                <div class="readonly-value">{{ $user->name }}</div>
                            </div>
                            <div class="account-field">
                                <label>Email address</label>
                                <div class="readonly-value">{{ $user->email }}</div>
                            </div>
                            <div class="account-field">
                                <label>Phone</label>
                                <div class="readonly-value">{{ $user->phone ?: 'Not added' }}</div>
                            </div>
                        </div>

                        <form action="{{ route('account.profile.update') }}" method="POST" class="js-profile-edit d-none">
                            @csrf
                            <div class="account-field-grid">
                                <div class="account-field full">
                                    <label>Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="account-field">
                                    <label>Email address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="account-field">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone number">
                                </div>
                            </div>
                            <div class="account-actions" style="justify-content:flex-end;">
                                <button type="button" class="account-muted-btn js-cancel-profile">Cancel</button>
                                <button type="submit" class="account-save-btn">Update Profile</button>
                            </div>
                        </form>

                        <div class="account-actions js-default-actions">
                            <button type="button" class="account-outline-btn js-change-password">Change Password</button>
                        </div>

                        <form action="{{ route('account.password.update') }}" method="POST" class="js-password-edit d-none">
                            @csrf
                            <div class="account-field-grid">
                                <div class="account-field">
                                    <label>New password</label>
                                    <div class="password-field-wrap">
                                        <input type="password" name="password" class="js-password-input" required>
                                        <button type="button" class="password-eye-btn js-password-toggle" aria-label="Show password"><i class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="account-field">
                                    <label>Confirm new password</label>
                                    <div class="password-field-wrap">
                                        <input type="password" name="password_confirmation" class="js-password-input" required>
                                        <button type="button" class="password-eye-btn js-password-toggle" aria-label="Show password"><i class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="account-actions" style="justify-content:flex-end;">
                                <button type="button" class="account-muted-btn js-cancel-password">Cancel</button>
                                <button type="submit" class="account-save-btn">Update Password</button>
                            </div>
                        </form>
                    </div>

                    <div class="account-panel js-account-panel d-none" data-account-panel="orders">
                        <div class="account-section-head">
                            <h2>My Orders</h2>
                        </div>

                        @forelse(($orders ?? collect()) as $order)
                            @php
                                $orderItems = $order->items ?? collect();
                                $mainItem = $order->main_item ?? $orderItems->first();
                                $address = $order->shipping_address_record ?? null;
                                $status = trim((string) ($order->status ?? 'Pending')) ?: 'Pending';
                                $orderNumber = $order->order_number ?? $order->order_id ?? ('ORD-' . $order->id);
                                $addressLine = collect([
                                    $address->address_line_one ?? $order->shipping_street ?? null,
                                    $address->address_line_two ?? null,
                                    $address->city ?? $order->shipping_city ?? null,
                                    $address->state ?? $order->shipping_state ?? null,
                                    $address->pincode ?? $order->shipping_pincode ?? null,
                                ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');
                                $receiverName = trim((string) (($address->firstname ?? '') . ' ' . ($address->secondname ?? '')))
                                    ?: ($order->shipping_name ?? $user->name);
                                $receiverPhone = $address->address_phone_number ?? $order->shipping_phone ?? null;
                            @endphp

                            @if($loop->first)
                                <div class="account-orders-list js-orders-list">
                                    <div class="luxury-orders-grid">
                            @endif
                                        <div class="luxury-order-card">
                                            <div class="loc-header">
                                                <div class="loc-id">Order <span>#{{ $orderNumber }}</span></div>
                                                <div class="loc-status status-{{ strtolower($status) }}">{{ $status }}</div>
                                            </div>
                                            <div class="loc-body">
                                                <div class="loc-meta">
                                                    <span class="loc-label">Payment</span>
                                                    <span class="loc-value">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                                                </div>
                                                <div class="loc-meta">
                                                    <span class="loc-label">Date</span>
                                                    <span class="loc-value">{{ optional($order->created_at ? \Carbon\Carbon::parse($order->created_at) : null)->format('M d, Y') }}</span>
                                                </div>
                                                <div class="loc-meta">
                                                    <span class="loc-label">Total Amount</span>
                                                    <span class="loc-value">₹{{ number_format((float) ($order->total_amount ?? 0), 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="loc-footer">
                                                <button type="button" class="loc-view-btn js-view-order" data-order-id="{{ $order->id }}">View Details &rarr;</button>
                                            </div>
                                        </div>
                            @if($loop->last)
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="account-empty-box">No orders found.</div>
                        @endforelse

                        @foreach(($orders ?? collect()) as $order)
                            @php
                                $orderItems = $order->items ?? collect();
                                $mainItem = $order->main_item ?? $orderItems->first();
                                $address = $order->shipping_address_record ?? null;
                                $status = trim((string) ($order->status ?? 'Pending')) ?: 'Pending';
                                $orderNumber = $order->order_number ?? $order->order_id ?? ('ORD-' . $order->id);
                                $addressLine = collect([
                                    $address->address_line_one ?? $order->shipping_street ?? null,
                                    $address->address_line_two ?? null,
                                    $address->city ?? $order->shipping_city ?? null,
                                    $address->state ?? $order->shipping_state ?? null,
                                    $address->pincode ?? $order->shipping_pincode ?? null,
                                ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');
                                $receiverName = trim((string) (($address->firstname ?? '') . ' ' . ($address->secondname ?? '')))
                                    ?: ($order->shipping_name ?? $user->name);
                                $receiverPhone = $address->address_phone_number ?? $order->shipping_phone ?? null;
                            @endphp
                            <div class="account-order-detail js-order-detail d-none" data-order-id="{{ $order->id }}">
                                <button type="button" class="js-back-orders" style="margin-bottom:24px; background:transparent; border:none; color:#64748B; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:2px; display:inline-flex; align-items:center; gap:8px; cursor:pointer;">&larr; Back to Orders</button>

                                <div class="account-order-detail-head">
                                    <img class="account-order-thumb" src="{{ $mainItem->image_url ?? asset('images/product/01.jpg') }}" alt="{{ $mainItem->product_name ?? 'Order item' }}">
                                    <div>
                                        <span class="account-status-badge">{{ $status }}</span>
                                        <h2 class="mt-3 mb-0">Order #{{ $orderNumber }}</h2>
                                    </div>
                                </div>

                                <div class="account-order-detail-grid">
                                    <div>
                                        <span class="account-detail-label">Payment Status</span>
                                        <div class="account-detail-value">{{ ucfirst($order->payment_status ?? 'pending') }}</div>
                                    </div>
                                    <div>
                                        <span class="account-detail-label">Main Item</span>
                                        <div class="account-detail-value">{{ $mainItem->product_name ?? 'Item not available' }}</div>
                                    </div>
                                    <div>
                                        <span class="account-detail-label">Courier</span>
                                        <div class="account-detail-value">{{ $order->courier_name }}</div>
                                    </div>
                                    <div>
                                        <span class="account-detail-label">Start Time</span>
                                        <div class="account-detail-value">{{ optional($order->created_at ? \Carbon\Carbon::parse($order->created_at) : null)->format('d M Y, H:i:s') }}</div>
                                    </div>
                                    <div>
                                        <span class="account-detail-label">Address</span>
                                        <div class="account-detail-value">{{ $addressLine ?: 'Address not available' }}</div>
                                    </div>
                                </div>

                                <div class="account-order-tabs">
                                    <button type="button" class="active js-order-tab" data-order-tab="history" data-order-id="{{ $order->id }}">Order History</button>
                                    <button type="button" class="js-order-tab" data-order-tab="items" data-order-id="{{ $order->id }}">Item Details</button>
                                    <button type="button" class="js-order-tab" data-order-tab="courier" data-order-id="{{ $order->id }}">Courier</button>
                                    <button type="button" class="js-order-tab" data-order-tab="receiver" data-order-id="{{ $order->id }}">Receiver</button>
                                </div>

                                <div class="account-order-tab-panel js-order-tab-panel" data-order-tab-panel="history" data-order-id="{{ $order->id }}">
                                    <div class="account-detail-value">{{ $status }}</div>
                                    <div>{{ optional($order->created_at ? \Carbon\Carbon::parse($order->created_at) : null)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="account-order-tab-panel js-order-tab-panel d-none" data-order-tab-panel="items" data-order-id="{{ $order->id }}">
                                    <div class="account-order-items">
                                        @forelse($orderItems as $item)
                                            @php
                                                $itemProductSlug = null;
                                                if (!empty($item->product_id)) {
                                                    $itemProductSlug = $item->slug ?? $item->product_id;
                                                }
                                            @endphp
                                            <div class="account-order-item" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 14px 16px; border-bottom: 1px solid #E2E8F0;">
                                                <div style="flex: 1 1 200px;">
                                                    <div class="account-detail-value" style="font-weight: 700; color: #0F172A; font-size: 14px;">{{ $item->product_name }}</div>
                                                    @if(!empty($item->variant_name))
                                                        <div style="font-size: 12px; color: #888;">Variant: {{ $item->variant_name }}</div>
                                                    @endif
                                                    <div style="font-size: 12px; color: #64748B; margin-top: 3px;">Qty: {{ (int) $item->quantity }} &bull; Price: ₹{{ number_format((float) $item->price, 2) }} &bull; Total: ₹{{ number_format((float) $item->total, 2) }}</div>
                                                </div>
                                                @if($itemProductSlug)
                                                    <div>
                                                        <a href="{{ url('/single-product?product=' . $itemProductSlug) }}#tabReviews" class="account-review-cta" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #080808; color: #D4AF37; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; border-radius: 4px; border: 1px solid #D4AF37; text-decoration: none; transition: all 0.2s ease;">
                                                            <i class="fa fa-star" style="color: #D4AF37; font-size: 11px;"></i> Write Review
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="account-empty-box">No items found for this order.</div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="account-order-tab-panel js-order-tab-panel d-none" data-order-tab-panel="courier" data-order-id="{{ $order->id }}">
                                    <div class="account-detail-value">{{ $order->courier_name }}</div>
                                    @if(!empty($order->shiprocket_shipment_id))
                                        <div>Shipment ID: {{ $order->shiprocket_shipment_id }}</div>
                                    @endif
                                    @if(!empty($order->shiprocket_order_id))
                                        <div>Shiprocket Order ID: {{ $order->shiprocket_order_id }}</div>
                                    @endif
                                </div>
                                <div class="account-order-tab-panel js-order-tab-panel d-none" data-order-tab-panel="receiver" data-order-id="{{ $order->id }}">
                                    <div class="account-detail-value">{{ $receiverName }}</div>
                                    @if($receiverPhone)
                                        <div>Ph: {{ $receiverPhone }}</div>
                                    @endif
                                    <div>{{ $addressLine ?: 'Address not available' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="account-panel js-account-panel d-none" data-account-panel="address">
                        <div class="account-section-head">
                            <h2>My Addresses</h2>
                            <button type="button" class="account-save-btn js-show-address-form">Add New Address</button>
                        </div>

                        <div class="account-address-grid">
                            @forelse(($addresses ?? collect()) as $address)
                                @php
                                    $isDefault = property_exists($address, 'is_default') && (int) $address->is_default === 1;
                                @endphp
                                <div class="account-address-card {{ $isDefault ? 'default' : '' }}">
                                    <div class="account-address-body">
                                        @if($isDefault)
                                            <span class="account-default-badge">Default</span>
                                        @endif
                                        <h3>{{ $address->address_type_name ?: 'Address' }}</h3>
                                        <p>
                                            {{ trim(($address->address_first_name ?? '') . ' ' . ($address->address_last_name ?? '')) ?: $user->name }}<br>
                                            {{ $address->address_line_one }}<br>
                                            @if(!empty($address->address_line_two)) {{ $address->address_line_two }}<br> @endif
                                            @if(!empty($address->landmark)) {{ $address->landmark }}<br> @endif
                                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                                            Ph: {{ $address->address_phone_number }}
                                        </p>
                                    </div>
                                    <div class="account-address-actions">
                                        <button type="button" class="js-edit-address" data-address-id="{{ $address->id }}"><i class="fa fa-pencil"></i> Edit</button>
                                        @if(!$isDefault)
                                            <form action="{{ route('account.address.default', $address->id) }}" method="POST">
                                                @csrf
                                                <button type="submit">Set Default</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('account.address.delete', $address->id) }}" method="POST" id="delete-address-form-{{ $address->id }}">
                                            @csrf
                                            <button type="button" class="btn-sm btn-def btn2" onclick="confirmAddressDelete({{ $address->id }})">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="account-empty-box">No address added yet.</div>
                            @endforelse
                        </div>

                        @include('pages.partials.account-address-form', ['formMode' => 'add', 'address' => null])

                        @foreach(($addresses ?? collect()) as $address)
                            @include('pages.partials.account-address-form', ['formMode' => 'edit', 'address' => $address])
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>

<script>
    function showAccountPanel(panelName) {
        document.querySelectorAll('.js-account-panel').forEach(function (panel) {
            panel.classList.toggle('d-none', panel.dataset.accountPanel !== panelName);
        });

        document.querySelectorAll('.js-account-tab').forEach(function (tab) {
            tab.classList.toggle('active', tab.dataset.accountTarget === panelName);
        });

        const editProfile = document.querySelector('.js-edit-profile');
        if (editProfile) {
            editProfile.classList.toggle('d-none', panelName !== 'profile');
        }

        if (panelName === 'orders') {
            document.querySelector('.js-orders-list')?.classList.remove('d-none');
            document.querySelectorAll('.js-order-detail').forEach(function (detail) {
                detail.classList.add('d-none');
            });
        }
    }

    document.addEventListener('click', function (event) {
        const accountTab = event.target.closest('.js-account-tab');
        if (accountTab) {
            event.preventDefault();
            const panelName = accountTab.dataset.accountTarget || 'profile';
            window.location.hash = panelName;
            showAccountPanel(panelName);
        }

        if (event.target.closest('.js-edit-profile')) {
            document.querySelector('.js-profile-view').classList.add('d-none');
            document.querySelector('.js-default-actions').classList.add('d-none');
            document.querySelector('.js-password-edit').classList.add('d-none');
            document.querySelector('.js-profile-edit').classList.remove('d-none');
        }

        if (event.target.closest('.js-cancel-profile')) {
            document.querySelector('.js-profile-edit').classList.add('d-none');
            document.querySelector('.js-profile-view').classList.remove('d-none');
            document.querySelector('.js-default-actions').classList.remove('d-none');
        }

        if (event.target.closest('.js-change-password')) {
            document.querySelector('.js-profile-edit').classList.add('d-none');
            document.querySelector('.js-password-edit').classList.remove('d-none');
        }

        if (event.target.closest('.js-cancel-password')) {
            document.querySelector('.js-password-edit').classList.add('d-none');
        }

        if (event.target.closest('.js-show-address-form')) {
            document.querySelectorAll('.js-address-edit-form').forEach(function (form) {
                form.classList.add('d-none');
            });
            document.querySelector('.js-address-add-form').classList.remove('d-none');
        }

        if (event.target.closest('.js-cancel-address-add')) {
            document.querySelector('.js-address-add-form').classList.add('d-none');
        }

        const editAddress = event.target.closest('.js-edit-address');
        if (editAddress) {
            document.querySelector('.js-address-add-form').classList.add('d-none');
            document.querySelectorAll('.js-address-edit-form').forEach(function (form) {
                form.classList.toggle('d-none', form.dataset.addressFormId !== editAddress.dataset.addressId);
            });
        }

        if (event.target.closest('.js-cancel-address-edit')) {
            event.target.closest('.js-address-edit-form').classList.add('d-none');
        }

        const viewOrder = event.target.closest('.js-view-order');
        if (viewOrder) {
            document.querySelector('.js-orders-list')?.classList.add('d-none');
            document.querySelectorAll('.js-order-detail').forEach(function (detail) {
                detail.classList.toggle('d-none', detail.dataset.orderId !== viewOrder.dataset.orderId);
            });
        }

        if (event.target.closest('.js-back-orders')) {
            document.querySelectorAll('.js-order-detail').forEach(function (detail) {
                detail.classList.add('d-none');
            });
            document.querySelector('.js-orders-list')?.classList.remove('d-none');
        }

        const orderTab = event.target.closest('.js-order-tab');
        if (orderTab) {
            const orderId = orderTab.dataset.orderId;
            const tabName = orderTab.dataset.orderTab;

            document.querySelectorAll('.js-order-tab[data-order-id="' + orderId + '"]').forEach(function (tab) {
                tab.classList.toggle('active', tab.dataset.orderTab === tabName);
            });

            document.querySelectorAll('.js-order-tab-panel[data-order-id="' + orderId + '"]').forEach(function (panel) {
                panel.classList.toggle('d-none', panel.dataset.orderTabPanel !== tabName);
            });
        }

        const toggle = event.target.closest('.js-password-toggle');
        if (toggle) {
            const input = toggle.closest('.password-field-wrap').querySelector('.js-password-input');
            const icon = toggle.querySelector('i');
            const shouldShow = input.type === 'password';

            input.type = shouldShow ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !shouldShow);
            icon.classList.toggle('fa-eye-slash', shouldShow);
        }
    });

    const initialPanel = ['#address', '#orders'].includes(window.location.hash) ? window.location.hash.slice(1) : 'profile';
    showAccountPanel(initialPanel);

    window.confirmAddressDelete = function(addressId) {
        Swal.fire({
            title: 'Delete this address?',
            text: 'You will not be able to recover this address.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-address-form-' + addressId).submit();
            }
        });
    };
</script>
@endsection
