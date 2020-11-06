#!/bin/sh
echo 'build private key'
openssl genrsa -out rsa_private_key.pem 1024


echo 'build public key'
openssl rsa -in ./rsa_private_key.pem -pubout -out rsa_public_key.pem


echo 'build csr'
openssl req -new -key rsa_private_key.pem -out hyperf.csr