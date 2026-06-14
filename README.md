<h2 align="center">Adobe Stock Community</h2>

<p align="center">
  Join our Telegram group to see how the bot works, view examples, and get updates.
</p>

<p align="center">
  <a href="https://t.me/AdobeStock4U" target="_blank">
    <img src="https://img.shields.io/badge/Join%20Telegram%20Group-26A5E4?style=for-the-badge&logo=telegram&logoColor=white" alt="Telegram Group">
  </a>
</p>

<p align="center">
  Explore the Adobe Stock bot, learn how it works, and discover available features directly in our Telegram community.
</p>

<div class="telegram-webhook-guide">

    <h2 class="guide-title">Configure Telegram Webhook Security</h2>

    <div class="guide-step">
        <h3 class="step-title">Step 1: Open BotFather</h3>
        <p class="step-description">
            Open BotFather in Telegram and select your bot.
        </p>
    </div>

    <div class="guide-step">
        <h3 class="step-title">Step 2: Generate a Secret Token</h3>
        <p class="step-description">
            Create a random secret token and save it in your bot configuration.
        </p>

        <div class="copy-box">
            <code id="secretTokenExample">MY_SECRET_TOKEN_123456789</code>
            <button class="copy-btn" onclick="copyText('secretTokenExample')">
                Copy
            </button>
        </div>
    </div>

    <div class="guide-step">
        <h3 class="step-title">Step 3: Set Your Webhook</h3>

        <div class="copy-box">
            <code id="webhookCommand">
https://api.telegram.org/botYOUR_BOT_TOKEN/setWebhook?url=https://your-domain.com/webhook.php&secret_token=MY_SECRET_TOKEN_123456789
            </code>

            <button class="copy-btn" onclick="copyText('webhookCommand')">
                Copy
            </button>
        </div>
    </div>

    <div class="guide-step">
        <h3 class="step-title">Step 4: Verify Webhook Status</h3>

        <div class="copy-box">
            <code id="webhookInfo">
https://api.telegram.org/botYOUR_BOT_TOKEN/getWebhookInfo
            </code>

            <button class="copy-btn" onclick="copyText('webhookInfo')">
                Copy
            </button>
        </div>
    </div>

</div>

<script>
function copyText(id) {
    const text = document.getElementById(id).innerText;

    navigator.clipboard.writeText(text).then(() => {
        alert('Copied successfully');
    });
}
</script>
