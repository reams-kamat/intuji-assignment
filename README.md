Certainly! Here's an updated README message for your GitHub repository `https://github.com/reams-kamat/intuji-assignment`:

---

# Intuji Assignment - Google Calendar Integration

This project demonstrates how to integrate with Google Calendar API using PHP. It allows you to list, create, and delete events from a connected Google Calendar.

## Features

- List events from your Google Calendar.
- Create new events with a summary, start time, and end time.
- Delete events from your Google Calendar.
- Disconnect your Google Calendar account.

## Requirements

- PHP 7.2 or higher
- Composer
- Google Account

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/reams-kamat/intuji-assignment.git
cd intuji-assignment
```

### 2. Install dependencies

```bash
composer install
```

### 3. Set up Google OAuth credentials

#### Step 1: Create OAuth Client ID and Secret

1. Go to the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a new project or select an existing one.
3. Navigate to **APIs & Services > Credentials**.
4. Click **Create credentials > OAuth client ID**.
5. Select **Web application** as the application type.
6. Add `http://localhost/intuji-assignment/callback.php` as an authorized redirect URI.
7. Click **Create**.
8. Download the JSON file and save it as `credentials.json` in the project root.

### 4. Configure the application

Copy the `credentials.json` file you downloaded from Google Cloud Console to the root directory of this project.

### 5. Start the application

```bash
php -S localhost:8000
```

### 6. Open your browser

Navigate to [http://localhost:8000](http://localhost:8000) to use the application.

## Usage

1. Click on **Connect to Google Calendar** and follow the authorization process.
2. Once connected, you can:
   - **List Events**: View existing events on your Google Calendar.
   - **Create Event**: Add new events with a summary, start time, and end time.
   - **Delete Event**: Remove an existing event from your Google Calendar.
   - **Disconnect Account**: Disconnect your Google Calendar account from this application.

## Troubleshooting

- If you encounter any issues, make sure you have set up the `credentials.json` file correctly and that your web server (e.g., Apache, Nginx) is properly configured to run PHP.

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for bugs, questions, or feature requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

This README provides clear instructions on how to clone, set up dependencies, configure Google OAuth credentials, and run the project. It also outlines the features and usage of the application, along with troubleshooting tips and information on how to contribute or report issues.

Feel free to customize this README according to your project's specifics or additional features you might want to highlight.
