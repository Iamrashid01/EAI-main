#!/bin/bash
# Create SQS queue
aws sqs create-queue --queue-name grocer-orders

# Configure IAM policy
aws iam create-policy --policy-name grocer-sqs-access \
  --policy-document file://sqs-policy.json

# Get queue URL
QUEUE_URL=$(aws sqs get-queue-url --queue-name grocer-orders --query QueueUrl --output text)

# Update application properties
echo "aws.sqs.queue=$QUEUE_URL" >> src/main/resources/application.properties