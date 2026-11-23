<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscription Plans - MediaBay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <meta property="og:image" content="assets/images/logo/logo.jpeg">
    <link rel="icon" type="image/png" href="assets/images/logo/logo.jpeg"  />
    <link rel="stylesheet" href="assets/css/subscription.css" />
    <script src="assets/js/subscription.js" defer></script>
  </head>
  <body>
    <!-- Money-back Guarantee Banner -->
    <div class="guarantee-banner">
      <div class="guarantee-content">
        <span class="guarantee-icon">🛡️</span>
        <div class="guarantee-text">
          <strong>30-Day Money-Back Guarantee</strong>
          <p>
            Not satisfied? Get a full refund within 30 days. No questions asked.
          </p>
        </div>
      </div>
    </div>

    <div class="subscription-container">
      <!-- Back Button -->
      <button class="back-button" onclick="goBack()">
        <span class="back-arrow">←</span> Back
      </button>

      <!-- Header -->
      <div class="subscription-header">
        <h1>Choose Your Subscription Plan</h1>
        <p>
          We offer three plans. Choose the one that fits how much help you need.
        </p>

        <!-- Billing Toggle -->
        <div class="billing-toggle-wrapper">
          <label class="billing-toggle">
            <input type="checkbox" id="billingToggle" />
            <span class="toggle-slider"></span>
            <span class="toggle-label">
              <span class="monthly-label">Monthly</span>
              <span class="annual-label">Annual (Save 20%)</span>
            </span>
          </label>
        </div>
      </div>

      <!-- Slider Wrapper -->
      <div class="slider-wrapper">
        <!-- Plan Indicators -->
        <div class="plan-indicators">
          <span class="indicator-dot active" data-plan="0"></span>
          <span class="indicator-dot" data-plan="1"></span>
          <span class="indicator-dot" data-plan="2"></span>
        </div>

        <!-- Navigation Buttons -->
        <button
          class="slider-nav slider-nav-prev"
          id="prevBtn"
          aria-label="Previous plan"
        >
          &#10094;
        </button>
        <button
          class="slider-nav slider-nav-next"
          id="nextBtn"
          aria-label="Next plan"
        >
          &#10095;
        </button>

        <!-- Slider Container -->
        <div class="slider-container" id="sliderContainer">
          <!-- Plan A: The Safety Net -->
          <div class="plan-card">
            <div class="plan-tier">BASIC</div>
            <h2 class="plan-name">The Safety Net</h2>

            <!-- Pricing -->
            <div class="plan-pricing">
              <span class="price-currency">R</span>
              <span class="price-amount monthly-price">199</span>
              <span class="price-amount annual-price" style="display: none"
                >1920</span
              >
              <span class="price-period">/month</span>
              <span class="price-save annual-save" style="display: none"
                >Save R480/year</span
              >
            </div>

            <p class="plan-description">
              Small businesses that just want to make sure their website doesn't
              crash or get hacked. You don't need many changes, you just want
              peace of mind.
            </p>

            <div class="plan-features">
              <h3>What's Included:</h3>
              <ul class="feature-list">
                <li class="feature-item">
                  <span class="feature-icon">📅</span>
                  <span
                    ><strong>Weekly Software Updates</strong> - We update the
                    core software (like WordPress or plugins) that runs your
                    site. Old software is the #1 way hackers get in. We keep the
                    doors locked.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">💾</span>
                  <span
                    ><strong>Daily "Cloud" Backups</strong> - We take a full
                    copy of your website every single day and save it in a
                    secure, separate location. If your website crashes or you
                    accidentally delete a page, we can bring it back to life
                    instantly.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">🔍</span>
                  <span
                    ><strong>Weekly Uptime Monitoring</strong> - We have a robot
                    that checks your website every 24/7 to see if it is
                    online. If your website goes down, we know about it before
                    you do, and we start fixing it immediately.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">🛡️</span>
                  <span
                    ><strong>Daily Security Scans</strong> - We scan your site
                    daily for "malware" (viruses). If we find any, we clean it
                    up for free.</span
                  >
                </li>
              </ul>

              <div class="standout-feature">
                <strong>✨ The "Stand Out" Detail:</strong><br />
                Spam Comment Cleaning - We automatically delete those annoying
                spam comments on your blog so your site looks professional.
              </div>
            </div>

            <button
              class="plan-button"
              onclick="selectPlan('The Safety Net', 'R99')"
            >
              Choose This Plan
            </button>
          </div>

          <!-- Plan B: The Growing Business -->
          <div class="plan-card popular-badge">
            <div class="most-popular-badge">⭐ MOST POPULAR ⭐</div>
            <div class="plan-tier">STANDARD</div>
            <h2 class="plan-name">The Growing Business</h2>

            <!-- Pricing -->
            <div class="plan-pricing">
              <span class="price-currency">R</span>
              <span class="price-amount monthly-price">399</span>
              <span class="price-amount annual-price" style="display: none"
                >3840</span
              >
              <span class="price-period">/month</span>
              <span class="price-save annual-save" style="display: none"
                >Save R960/year</span
              >
            </div>

            <p class="plan-description">
              Businesses that are active. You post new photos, change prices, or
              write blogs often. You want your site to be faster and better than
              your competitors.
            </p>

            <div class="plan-features">
              <h3>Includes Everything in BASIC, PLUS:</h3>
              <ul class="feature-list">
                <li class="feature-item">
                  <span class="feature-icon">✏️</span>
                  <span
                    ><strong>1 Hour of "Content Support" per Month</strong> - Do
                    you need to change a paragraph of text? Swap out a photo?
                    Add a new team member to the "About Us" page? Just email us.
                    We will do it for you so you don't have to stress about the
                    software.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">⚡</span>
                  <span
                    ><strong>Speed Optimization</strong> - Users hate slow
                    websites. We compress your images and clean up the code
                    every month of the week to make sure your site loads fast on mobile
                    phones.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">🔗</span>
                  <span
                    ><strong>Monthly Broken Link Scans</strong> - Nothing looks
                    worse than a customer clicking a link and seeing "Page Not
                    Found." We scan your site monthly to find broken links and
                    fix them.</span
                  >
                </li>
              </ul>

              <div class="standout-feature">
                <strong>✨ The "Stand Out" Detail:</strong><br />
                Rollover Hours - If you don't use your 1 hour of support this
                month, it rolls over to the next month (up to 3 months). You
                don't lose the time you paid for!
              </div>
            </div>

            <button
              class="plan-button"
              onclick="selectPlan('The Growing Business', 'R199')"
            >
              Choose This Plan
            </button>
          </div>

          <!-- Plan C: The Partner -->
          <div class="plan-card">
            <div class="plan-tier">PREMIUM</div>
            <h2 class="plan-name">The Partner</h2>

            <!-- Pricing -->
            <div class="plan-pricing">
              <span class="price-currency">R</span>
              <span class="price-amount monthly-price">699</span>
              <span class="price-amount annual-price" style="display: none"
                >6,720</span
              >
              <span class="price-period">/month</span>
              <span class="price-save annual-save" style="display: none"
                >Save R1680/year</span
              >
            </div>

            <p class="plan-description">
              Serious businesses where the website brings in money (e-commerce,
              high traffic). You need us to be on call and act like your
              internal IT department.
            </p>

            <div class="plan-features">
              <h3>Includes Everything in BASIC & STANDARD, PLUS:</h3>
              <ul class="feature-list">
                <li class="feature-item">
                  <span class="feature-icon">🚀</span>
                  <span
                    ><strong>Priority Support (Skip the Line)</strong> - When
                    you email us, your ticket goes to the top of the pile. We
                    aim to reply within 2 hours during business days.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">👥</span>
                  <span
                    ><strong>2 Hours of Support per Month</strong> - More time
                    for us to make changes, add products, or design new landing
                    pages for you.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">📱</span>
                  <span
                    ><strong>Monthly Mobile Usability Check</strong> - Once a
                    month, a real human (not a robot) loads your site on an
                    iPhone and an Android to make sure buttons are easy to click
                    and text is easy to read.</span
                  >
                </li>
                <li class="feature-item">
                  <span class="feature-icon">📊</span>
                  <span
                    ><strong>Monthly Strategy Call (30 Mins)</strong> - We don't
                    just fix the site; we help you grow. We hop on a Zoom call
                    to discuss how the site is performing and what we can
                    improve next month.</span
                  >
                </li>
              </ul>

              <div class="standout-feature">
                <strong>✨ The "Stand Out" Detail:</strong><br />
                Video Reports - Instead of sending you a boring PDF with
                numbers, we send you a 2-minute video recording of your screen,
                showing you exactly what we did, how your traffic looks, and
                explaining it in plain English.
              </div>
            </div>

            <button
              class="plan-button"
              onclick="selectPlan('The Partner', 'R399')"
            >
              Choose This Plan
            </button>
          </div>
        </div>
      </div>

      <!-- Comparison Table Section -->
      <section class="comparison-section">
        <h2>Quick Comparison</h2>
        <div class="comparison-table-wrapper">
          <table class="comparison-table">
            <thead>
              <tr>
                <th>Feature</th>
                <th>Safety Net</th>
                <th>Growing Business</th>
                <th>The Partner</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Weekly Software Updates</td>
                <td>-</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Daily Cloud Backups</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>24/7 Uptime Monitoring</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Daily Security Scans</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Spam Comment Cleaning</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Content Support per Month</td>
                <td>-</td>
                <td>1 Hour</td>
                <td>2 Hours</td>
              </tr>
              <tr>
                <td>Speed Optimization</td>
                <td>-</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Broken Link Scans</td>
                <td>-</td>
                <td>✓</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Priority Support</td>
                <td>-</td>
                <td>-</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Mobile Usability Check</td>
                <td>-</td>
                <td>-</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Monthly Strategy Call</td>
                <td>-</td>
                <td>-</td>
                <td>✓</td>
              </tr>
              <tr>
                <td>Video Reports</td>
                <td>-</td>
                <td>-</td>
                <td>✓</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section class="testimonials-section">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-container">
          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-text">
              "MediaBay's team has been a lifesaver. Our website never goes
              down, and we sleep better at night knowing our data is backed up
              daily. The Safety Net plan is perfect for us!"
            </p>
            <div class="testimonial-author">
              <strong>Sarah Mitchell</strong>
              <span>Small Business Owner</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-text">
              "The content support hours have been invaluable. We were able to
              focus on growing our business while MediaBay handled all the
              technical updates and optimization. Highly recommend!"
            </p>
            <div class="testimonial-author">
              <strong>James Rodriguez</strong>
              <span>E-commerce Manager</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-text">
              "The Partner plan essentially gave us an internal IT team without
              the hiring costs. The strategy calls alone are worth it. Our
              traffic increased 40% after implementing their recommendations!"
            </p>
            <div class="testimonial-author">
              <strong>Emily Zhang</strong>
              <span>Digital Marketing Director</span>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-container">
          <div class="faq-item">
            <button class="faq-question">
              <span>Can I upgrade or downgrade my plan anytime?</span>
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                Absolutely! You can upgrade or downgrade your plan at any time.
                If you upgrade mid-month, we'll prorate the difference. If you
                downgrade, the change takes effect on your next billing cycle.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span
                >What if I need more than 2 hours of support per month?</span
              >
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                Great question! We offer additional support hours at R75/hour.
                Many of our Partner plan customers only use their included
                hours, but the option is always there if you need it. Plus, with
                the Growing Business plan, unused hours roll over for up to 3
                months.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Do you offer setup fees or hidden costs?</span>
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                Nope! The price you see is what you pay. No setup fees, no
                hidden charges, no surprise billing. We're upfront about
                everything. You'll get an invoice before your first charge so
                you know exactly what to expect.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Is there a contract? Can I cancel anytime?</span>
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                No long-term contracts. You can cancel your subscription at any
                time without penalties. If you cancel, you'll have access
                through the end of your current billing period. Plus, we offer a
                30-day money-back guarantee if you're not satisfied.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>What happens to my website if I cancel?</span>
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                Your website stays online! The updates and monitoring will stop,
                but we won't delete your site or data. You're free to move your
                website elsewhere or handle maintenance yourself. We just won't
                be managing it anymore.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Which plan is best for my business?</span>
              <span class="faq-toggle">+</span>
            </button>
            <div class="faq-answer">
              <p>
                It depends on how often you update your website and how much
                support you need. The Safety Net is great for businesses with
                static websites. Growing Business is perfect for active websites
                that need regular changes. The Partner plan is ideal for
                e-commerce or high-traffic sites where downtime costs money.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact/Support CTA Section -->
      <section class="contact-cta-section">
        <div class="contact-cta-content">
          <h2>Can't Decide? Let's Talk!</h2>
          <p>
            Not sure which plan is right for you? Our team is happy to help you
            find the perfect fit for your business needs.
          </p>
          <div class="contact-cta-buttons">
            <button class="cta-button primary" onclick="openContactForm()">
              Schedule a Free Consultation
            </button>
            <button class="cta-button secondary" onclick="sendEmail()">
              Email Us
            </button>
          </div>
          <p class="contact-note">
            We'll respond within 2 hours during business hours (9 AM - 5 PM EST,
            Monday - Friday)
          </p>
        </div>
      </section>

      <!-- Trust Badges Footer -->
      <footer class="trust-badges-footer">
        <div class="badges-container">
          <div class="trust-badge">
            <span class="badge-icon">🔒</span>
            <div class="badge-content">
              <strong>Bank-Level Security</strong>
              <p>256-bit SSL encryption protecting your data</p>
            </div>
          </div>
          <div class="trust-badge">
            <span class="badge-icon">✅</span>
            <div class="badge-content">
              <strong>99.9% Uptime Guarantee</strong>
              <p>We take your website availability seriously</p>
            </div>
          </div>
          <div class="trust-badge">
            <span class="badge-icon">⏱️</span>
            <div class="badge-content">
              <strong>24/7 Monitoring</strong>
              <p>Round-the-clock automated website checks</p>
            </div>
          </div>
          <div class="trust-badge">
            <span class="badge-icon">💬</span>
            <div class="badge-content">
              <strong>Expert Support Team</strong>
              <p>Real humans ready to help your business</p>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>
