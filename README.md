# Task-Manager-with-Email-Reminder

# Task Scheduler – PHP Task Manager with Email Reminders

This PHP project allows users to manage daily tasks and receive automated reminders.

✅ Task Management:
- Add, delete, and mark tasks complete/incomplete
- Tasks stored as JSON in `tasks.txt`

✅ Email Subscription:
- Users subscribe via email + verification code
- Verified emails stored in `subscribers.txt`

✅ Hourly Reminders:
- CRON job sends pending task reminders to verified subscribers
- Includes unsubscribe link

📌 Project Structure:
- All logic is inside the `src/` folder
- JSON-based file storage (no database)
- Email content formatted in HTML as per guidelines

🧰 Built with pure PHP & CRON  
🧠 Submission follows all instructions: no external libs, one-click unsubscribe, 6-digit verification, etc.

