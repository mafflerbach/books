#!/bin/sh -x

# $2 gitDir
cd $2

# $1 action
# $2 gitDir
# $3 git commit message
# $4 git email
# $5 git username
if [ "$1" = "commit" ]; then
    git add .
    if [ "$4" != "" ]; then
        git config user.email $4;
        git config user.name $5;
    fi
    git commit -m"'commit $3'"
fi

# $1 action
if [ "$1" = "listconf" ]; then
    git config --list;
fi

# $1 action
# $1 git Dir
if [ "$1" = "init" ]; then
    git init $1;
fi

