#!/usr/bin/env bash

echo copying plugin to $1

cp  -ruv ./src/modules/mo/mo_ingenico/ $1/modules/mo

echo finished