<?php
$page_title = 'Thank You';
$page_desc  = 'SEASTAR TECHNOLOGIES LLC is a U.S.-registered authorized reseller of leading consumer technology products including antivirus software, hardware, and accessories, located in Tampa, Florida.';
include 'includes/header.php';
?>

<div class="thank-you">
    <div class="wrapper">

    <div class="check-circle">
        <i class="fa-solid fa-check"></i>
    </div>

    <h1>Thank You!</h1>

    <p class="desc">
        Your support request has been received successfully.
        Our security specialists are reviewing your information
        and will contact you shortly.
    </p>

  

    <div class="call-box">
        <small>📞 Can't wait? Call us now!</small>
        <div class="phone">(855) 460-3303</div>
        <a href="tel:(855) 460-3303" class="call-btn">
            <i class="fa-solid fa-phone"></i>
            CALL NOW FOR IMMEDIATE SERVICE
        </a>
    </div>

</div>
</div>

<style>
:root {
    --primary: #ffffff;
    --secondary: #33322a;
    --dark: #0f172a;
    --text: #334155;
    --muted: #64748b;
    --soft: #f8fafc;
}

* {
    box-sizing: border-box;
}
.thank-you{
    display: flex;
    justify-content: center;
    padding: 18px 10px;
}

/* MAIN CARD */
.wrapper {
    width: 100%;
    max-width: 820px;
    background: #ffffff;
    border-radius: 26px;
    padding: 56px 56px 48px;
    box-shadow:
        0 30px 60px rgba(15,23,42,0.10),
        0 120px 200px rgba(15,23,42,0.12);
    text-align: center;
}

/* CHECK ICON */
.check-circle {
    width: 84px;
    height: 84px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 50%;
    display: grid;
    place-items: center;
    margin: 6px auto 22px;
    box-shadow: 0 14px 32px rgba(242,51,102,0.45);
}

.check-circle i {
    color: #fff;
    font-size: 38px;
}

/* TITLE */
h1 {
    margin: 0 0 14px;
    font-size: 40px;
    color: var(--dark);
}

.desc {
    font-size: 16px;
    color: var(--muted);
    line-height: 1.7;
    max-width: 620px;
    margin: 0 auto 36px;
}

/* ESTIMATE BOX */
.estimate {
    background: var(--soft);
    border-radius: 18px;
    padding: 24px 22px;
    margin-bottom: 34px;
}

.estimate-title {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 16px;
}

.timeline {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 12px;
}

.timeline span {
    width: 12px;
    height: 12px;
    background: var(--secondary);
    border-radius: 50%;
    opacity: 0.25;
}

.timeline span.active {
    opacity: 1;
}

.estimate-text {
    font-size: 13px;
    color: var(--muted);
}

/* CALL CARD */
.call-box {
    background: linear-gradient(180deg, #0f172a, #020617);
    border-radius: 20px;
    padding: 28px 26px;
    color: #fff;
    margin-bottom: 42px;
}

.call-box small {
    display: block;
    font-size: 14px;
    opacity: 0.85;
    margin-bottom: 10px;
}

.phone {
    font-size: 34px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 18px;
}

.call-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    text-decoration: none;
    padding: 16px 30px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 18px 40px rgba(242,51,102,0.45);
}

.call-btn:hover {
    transform: translateY(-2px);
}

/* STEPS */
.steps-title {
    font-weight: 800;
    font-size: 18px;
    margin-bottom: 20px;
    color: var(--dark);
}

.steps {
    text-align: left;
    max-width: 640px;
    margin: auto;
}

.step {
    display: flex;
    gap: 12px;
    font-size: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #e5e7eb;
    color: var(--text);
}

.step:last-child {
    border-bottom: none;
}

.step i {
    color: var(--secondary);
    margin-top: 2px;
}

/* FOOTER LINK */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 28px;
    font-size: 14px;
    color: var(--secondary);
    text-decoration: none;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .wrapper {
        padding: 42px 26px;
    }

    h1 {
        font-size: 30px;
    }
    .phone{
        font-size: 21px;
    }
}
</style>

<?php
// Include dynamic footer
include 'includes/footer.php';
?>