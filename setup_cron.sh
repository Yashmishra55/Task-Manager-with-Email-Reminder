#!/bin/bash

# Get full path to cron.php
CRON_FILE_PATH="$(pwd)/cron.php"

# Cron job line (runs every hour)
CRON_JOB="0 * * * * php $CRON_FILE_PATH"

# Check if crontab command is available
if ! command -v crontab &> /dev/null; then
    echo "❌ crontab command not found. This script must be run in a Linux environment or WSL."
    exit 1
fi

# Check if already scheduled
crontab -l 2>/dev/null | grep -F "$CRON_JOB" >/dev/null

if [ $? -eq 0 ]; then
    echo "ℹ️ CRON job is already scheduled."
else
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    echo "✅ CRON job added successfully. cron.php will run every hour."
fi
