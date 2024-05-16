#!/bin/bash
# Script to extract gradslam_files.tar.gz into the correct directory

# Target directory
TARGET_DIR="/app/web/sites/default"

# Ensure target directory exists
mkdir -p $TARGET_DIR

# Extract files
tar -xzf /app/grad_slam_files.tar.gz -C $TARGET_DIR
