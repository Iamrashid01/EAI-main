#!/bin/bash
echo "Creating SQS queue..."
aws --endpoint-url=http://localhost:4566 sqs create-queue --queue-name grocer-orders

echo "Updating application config..."
echo "aws.sqs.queue=grocer-orders" >> src/main/resources/application.properties
echo "aws.accessKey=test" >> src/main/resources/application.properties
echo "aws.secretKey=test" >> src/main/resources/application.properties