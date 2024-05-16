#!/bin/bash
# Script to import gradslam_database.sql.gz into the database

# Database credentials
DB_NAME="drupal10"
DB_USER="drupal10"
DB_PASS="drupal10"
DB_HOST="database"

# Path to the database dump file
DB_DUMP_FILE="/app/grad_slam_database.gz"

# Check if the database dump file exists
if [ ! -f "$DB_DUMP_FILE" ]; then
    echo "Error: Database dump file '$DB_DUMP_FILE' not found."
    exit 1
fi

# Import database dump
gunzip -c "$DB_DUMP_FILE" | mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"

# Check the exit status of the mysql command
if [ $? -eq 0 ]; then
    echo "Database import successful."
else
    echo "Error: Failed to import database."
    exit 1
fi
