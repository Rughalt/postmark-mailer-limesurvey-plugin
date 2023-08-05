# Postmark Mailer LimeSurvey Plugin

![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)

The `postmarkMailer` plugin for LimeSurvey allows you to send emails using the Postmark API as the email delivery service. This plugin integrates with LimeSurvey's email sending process and utilizes the Postmark API to deliver emails.

## Features

- Send emails using the Postmark API.
- Configure the Postmark API Key and From Email address.
- Choose between 'Broadcast' and 'Transactional' message streams for sending emails.

## Requirements

- LimeSurvey version: **4.x** or later
- PHP: **7.2** or later
- A valid Postmark account and API credentials.

## Installation

### Method 1: Zip Upload (Recommended)

1. Download the latest release of the plugin from the [Releases](https://github.com/rughalt/postmark-mailer-limesurvey-plugin/releases) page.
2. Login to your LimeSurvey admin panel.
3. Navigate to "Configuration" -> "Plugins"
4. Click the "Upload & Install" button.
5. Select the zip file you downloaded in Step 1 (`postmarkMailer.zip`).
6. Click the "Install" button to install the plugin.
7. Find the "postmarkMailer" plugin in the list and click the "Activate" button.

### Method 2: Manual Installation

1. Download the latest release of the plugin from the [Releases](https://github.com/rughalt/postmark-mailer-limesurvey-plugin/releases) page.
2. Unzip the plugin package.
3. Upload the `postmarkMailer` folder to the `upload/plugins` directory of your LimeSurvey installation.
4. Login to your LimeSurvey admin panel.
5. Navigate to "Configuration" -> "Plugins"
6. Click the "Scan Files" button to install the plugin.
7. Find the "postmarkMailer" plugin in the list and click the "Activate" button.

## Configuration

After activating the plugin, you need to configure the Postmark API Key and From Email address.

1. Go to "Configuration" -> "Global Settings" -> "Plugin manager."
2. Click on "postmarkMailer" in the list of plugins.
3. Fill in the required settings:

   - **Postmark API Key**: Enter your Postmark API Key.
   - **Postmark From Email**: Enter the email address from which you want to send emails.
   - **Message Stream**: Choose between 'Broadcast' and 'Transactional (Outbound)' for sending emails.

4. Click the "Save" button to save the settings.

## Usage

Once the plugin is configured, it will automatically hook into LimeSurvey's email sending process. When LimeSurvey sends an email, the `postmarkMailer` plugin will utilize the Postmark API to deliver the email. If there are any errors during the email delivery, the plugin will handle and log them.

## License

This plugin is released under the [MIT License](LICENSE).

## Author

- **Antoni "Rughalt" Sobkowicz**
