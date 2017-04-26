#!/bin/bash

# @copyright (c) Mediaopt GmbH | 2017
# @link https://bitbucket.org/mediaopt/build-brand
# @link www.mediaopt.de
# @version 1.0.0


echo ""
echo -e "\e[42m                                  \e[0m"
echo -e "\e[42mWelcome to the build-brand wizard!\e[0m"
echo -e "\e[42m                                  \e[0m"

#########################################
############# HELP ######################
#########################################
HELP="\n\n\e[1mThis is a script to create/update repository from existing\e[21m

\e[0;33mUsage:\e[0m
    build-brand
    build-brand --brand concardis
    build-brand --brand concardis --create-config --config-filename my-config.cfg
    build-brand -n --config-filename my-config.cfg

\e[0;33mOptions:\e[0m
\e[0;32m -n, --no-interaction\e[0m
    Do not ask any interactive question. This option should not be the last one!

\e[0;32m --create-config\e[0m
    Do not clone the repository. Create/update a config file in a working directory only. This option should not be the last one!

\e[0;32m --working-dir\e[0m
    Specify a working directory.

\e[0;32m --brand\e[0m
    Specify a brand name.

\e[0;32m --tag\e[0m
    Specify a tag name. All changes in a new cloned repository will be committed and tagged.

\e[0;32m --branch\e[0m
    Specify a branch name. All changes in a new cloned repository will be done on this branch only. Branch should exists!

\e[0;32m --path-to-icon\e[0m
    Specify a relative path to the plugin icon to be replaced [path/relative/to/repo/root/icon.png].

\e[0;32m --config-filename\e[0m
    Specify a relative path to a config file to be used/created for this script [path/relative/to/repo/root/build-brand.cfg].

\e[0;32m --git-url-base\e[0m
    Base url for a git repository [git@github.com:bubnov-mihail/].

\e[0;32m --git-url\e[0m
    Full url to a git repository [git@github.com:bubnov-mihail/build-brand.git].

\e[0;32m -h\e[0m
    Show this help text
"

for i in "$@"
do
    case $i in
        -h)
        echo -e "$HELP";
        exit 0
        ;;
    esac
done

#########################################
############# CONFIG ####################
#########################################


# Exit codes

WRONG_PARAM_ERROR_CODE=126

#no-interaction mode
NO_INT=false

#Create config instead of repository cloning
CREATE_CONFIG=false

# Git url base
declare -gA CONFIG
CONFIG['git-url-base']='git@bitbucket.org:mediaopt/'
CONFIG['git-url']=''
CONFIG['brand']=''
CONFIG['working-dir']=$PWD
CONFIG['tag']=''
CONFIG['branch']='master'
CONFIG['config-filename']='build-brand.cfg'
CONFIG['path-to-icon']=''

declare -gA ICONS
declare -gA REPLACE_IN_FILES
declare -gA RENAME_FILES

# Static paths
THIS_FILE=$(realpath "$0")
LIB_DIR=$(dirname "$THIS_FILE")


# Icons auto collecting
collectIcons() {
    export ICONS
    ICON_FILES=$LIB_DIR/customIcons/*.png
    for f in $ICON_FILES
    do
      ICON_FILENAME=$(basename $f)
      brand="${ICON_FILENAME/\.png/}"
      ICONS["$brand"]=$ICON_FILENAME
    done
}

collectIcons

##########################################################
############## CONFIG OVERRIDE ###########################
##########################################################

overrideConfig() {
    local maxArgs=${#BASH_ARGV[@]}
    local n
    ((n=maxArgs-1))

    export CONFIG
    export NO_INT
    export CREATE_CONFIG

    while [[ $n -gt 0 ]]
    do
        local key="${BASH_ARGV[$n]}"
        local val_i=0
        if [ $n -gt 1 ] ; then
            ((val_i=n-1))
        fi
            
        local val="${BASH_ARGV[$val_i]}"

        case $key in
            -n|--no-interaction)
            NO_INT=true
            ;;
            --create-config)
            CREATE_CONFIG=true
            ;;
            --working-dir)
            CONFIG['working-dir']="$val"
            ((n--))
            ;;
            --git-url-base)
            CONFIG['git-url-base']="$val"
            ((n--))
            ;;
            --brand)
            CONFIG['brand']="$val"
            ((n--))
            ;;
            --git-url)
            CONFIG['git-url']="$val"
            ((n--))
            ;;
            --tag)
            CONFIG['tag']="$val"
            ((n--))
            ;;
            --branch)
            CONFIG['branch']="$val"
            ((n--))
            ;;
            --path-to-icon)
            CONFIG['path-to-icon']="$val"
            ((n--))
            ;;
            --config-filename)
            CONFIG['config-filename']="$val"
            ((n--))
            ;;
        esac
        ((n--))
    done
}

overrideConfig

exitWithError() {
    msg=$1
    echo -e "\e[0;31m$msg\nAborting.\e[0m";
    exit $WRONG_PARAM_ERROR_CODE
}

configPath="${CONFIG['working-dir']}/${CONFIG['config-filename']}"
if [ -f "$configPath" ] ; then
    echo -e "\e[0;33m\nLoading config from $configPath...\e[0m"
    source $configPath
    echo -e "\e[0;32mOk.\e[0m"
fi

overrideConfig

validateConfig() {
    echo -e "\e[0;33m\nValidating config...\e[0m"
    
    # In no-interaction mode all required vars MUST be defined
    if $NO_INT ; then
        if [ -z "${CONFIG['brand']}" ] ; then
            exitWithError "In no-interaction mode brand param is required";
        fi
        
        if [ -z "${CONFIG['working-dir']}" ] ; then
            exitWithError "In no-interaction mode working-dir param is required";
        fi

        if [ -z "${CONFIG['git-url-base']}" ] ; then
            exitWithError "In no-interaction mode git-url-base param is required";
        fi

        if [ -z "${CONFIG['git-url']}" ] ; then
            exitWithError "In no-interaction mode git-url param is required";
        fi
    fi

    # In create-config mode config-filename is required
    if [[ $CREATE_CONFIG && -z ${CONFIG['config-filename']} ]]; then
        exitWithError "In create-config mode config-filename is required.";
    fi
    
    echo -e "\e[0;32mOk.\e[0m"
}
validateConfig


##########################################################
###################### QUESTIONS #########################
##########################################################


##########################################################
# New brand name
##########################################################
if $NO_INT ; then
    echo -e "\e[0;33m\nBrand name\e[0m [${CONFIG['brand']}]:"
else
    echo -e "\e[0;34m\nEnter brand name\e[0m [${CONFIG['brand']}]:"
    read brand_i
    if [ -n "$brand_i" ] ; then
        CONFIG['brand']="$brand_i"
    fi
fi
##########################################################


##########################################################
# Git url for a brand repository
##########################################################
if [ -z "${CONFIG['git-url']}" ] ; then
  CONFIG['git-url']="${CONFIG['git-url-base']}${CONFIG['brand']}.git"
fi

if $NO_INT ; then
    echo -e "\e[0;33m\nGit url for the brand repository\e[0m [${CONFIG['git-url']}]:"
else
    echo -e "\e[0;34m\nEnter git url for the brand repository\e[0m [${CONFIG['git-url']}]:"
    read git_url_i
    if [ -n "$git_url_i" ] ; then
        CONFIG['git-url']=$git_url_i
    fi
fi
##########################################################


##########################################################
# Tag changes
##########################################################
if $NO_INT ; then
    echo -e "\e[0;33m\nCreate new/replace tag\e[0m [${CONFIG['tag']}]:"
else
    echo -e "\e[0;34m\nEnter name for a new/replace tag\e[0m [${CONFIG['tag']}]:"
    read new_tag_i
    if [ -n "$new_tag_i" ] ; then
        CONFIG['tag']="$new_tag_i"
    fi
fi
##########################################################


##########################################################
# Path to icon to be replaced
##########################################################
if $NO_INT ; then
    echo -e "\e[0;33m\nRelative path (to the repository root) to the icon to be replaced\e[0m\n[${CONFIG['path-to-icon']}]"
else
    echo -e "\e[0;34m\nEnter a relative path (to the repository root) to the icon to be replaced\e[0m\n[${CONFIG['path-to-icon']}]"
    read path_to_icon_i
    if [ -n "$path_to_icon_i" ] ; then
        CONFIG['path-to-icon']="$path_to_icon_i"
    fi
fi


##########################################################
# Replace in files
##########################################################

showSearchAndReplacePairs() {
    for i in "${!REPLACE_IN_FILES[@]}"
    do
        key="$i"
        value="${REPLACE_IN_FILES[$i]}"
        echo -e "\e[0;33mWill search for '\e[0m$key\e[0;33m' and replace with '\e[0m$value\e[0;33m'\e[0m"
    done
}

if $NO_INT ; then
    echo -e "\e[0;33m\nWill use REPLACE_IN_FILES config parameters:\e[0m"
    showSearchAndReplacePairs
else
    echo -e "\e[0;33m\nSearch and replace pairs mode...\e[0m"

    if [ ${#REPLACE_IN_FILES[@]} -ne 0 ]; then
        echo -e "\e[0;33mThere are some search and replace pairs already defined in the config file:\e[0m"
        showSearchAndReplacePairs
        echo -e "\e[0;34m\nShould I clear them?\e[0m [n]/y"
        read replace_clear_i
        if [[ -n "$replace_clear_i" && "$replace_clear_i" -eq 'y' ]] ; then
            REPLACE_IN_FILES=()
            echo -e "\e[0;32mCleared.\n\e[0m"
        fi
    fi

    addSearchAndReplacePair() {
        export REPLACE_IN_FILES
        echo -e "\e[0;34m\nEnter a string to be searched.\nOr hit Enter to quit this mode and continue.\e[0m"
        read search_i
        if [ -z "$search_i" ] ; then
            echo -e "\e[0;32mOk.\n\e[0m"
            return
        else
            echo -e "\e[0;34m\nEnter a replace string.\nOr hit Enter to quit this mode and continue.\e[0m"
            read replace_i
            if [ -z "$replace_i" ] ; then
                echo -e "\e[0;32mOk.\n\e[0m"
                return
            else
                REPLACE_IN_FILES["$search_i"]="$replace_i"
                addSearchAndReplacePair
            fi
        fi
    }
    addSearchAndReplacePair
    showSearchAndReplacePairs
fi

##########################################################



##########################################################
# Rename files
##########################################################

showRenamePairs() {
    for i in "${!RENAME_FILES[@]}"
    do
        key="$i"
        value="${RENAME_FILES[$i]}"
        echo -e "\e[0;33mWill rename '\e[0m$key\e[0;33m' to '\e[0m$value\e[0;33m'\e[0m"
    done
}

if $NO_INT ; then
    echo -e "\e[0;33m\nWill use RENAME_FILES config parameters:\e[0m"
    showRenamePairs
else
    echo -e "\e[0;33m\nRename file pairs mode...\e[0m"

    if [ ${#RENAME_FILES[@]} -ne 0 ]; then
        echo -e "\e[0;33mThere are some rename pairs already defined in the config file:\e[0m"
        showRenamePairs
        echo -e "\e[0;34m\nShould I clear them?\e[0m [n]/y"
        read rename_clear_i
        if [[ -n "$rename_clear_i" && "$rename_clear_i" -eq 'y' ]] ; then
            RENAME_FILES=()
            echo -e "\e[0;32mCleared.\n\e[0m"
        fi
    fi

    addRenamePair() {
        export RENAME_FILES
        echo -e "\e[0;34m\nEnter a string to be searched in file/directory path or name.\nOr hit Enter to quit this mode and continue.\e[0m"
        read origin_i
        if [ -z "$origin_i" ] ; then
            echo -e "\e[0;32mOk.\n\e[0m"
            return
        else
            echo -e "\e[0;34m\nEnter a replace string.\nOr hit Enter to quit this mode and continue.\e[0m"
            read rename_i
            if [ -z "$rename_i" ] ; then
                echo -e "\e[0;32mOk.\n\e[0m"
                return
            else
                RENAME_FILES["$origin_i"]="$rename_i"
                addRenamePair
            fi
        fi
    }
    addRenamePair
    showRenamePairs
fi

##########################################################



##########################################################
################## END OF QUESTIONS ######################
##########################################################


saveConfig() {
    configPath="${CONFIG['working-dir']}/${CONFIG['config-filename']}"
    echo -e "\e[0;33m\nSaving config to $configPath\e[0m"

    # Create and clear config file
    touch $configPath
    echo "#Config for build-brand script. Feel free to modify it.">$configPath
    echo>>$configPath
    
    saveConfigParam() {
        param=$1
        echo "CONFIG['$param']='${CONFIG[$param]}'">>$configPath
    }
    saveConfigParam 'git-url-base'
    saveConfigParam 'git-url'
    saveConfigParam 'brand'
    saveConfigParam 'branch'
    saveConfigParam 'path-to-icon'
    
    for i in "${!REPLACE_IN_FILES[@]}"
    do
        key="$i"
        value="${REPLACE_IN_FILES[$i]}"
        echo "REPLACE_IN_FILES['$key']='$value'">>$configPath
    done

    for i in "${!RENAME_FILES[@]}"
    do
        key="$i"
        value="${RENAME_FILES[$i]}"
        echo "RENAME_FILES['$key']='$value'">>$configPath
    done

    echo -e "\e[0;32mOk. \e[0;33mDo not forget to track this new config file!\e[0m"
}

if $CREATE_CONFIG ; then
    saveConfig
    exit 0
fi


##########################################################
# Clone repository to a temporary directory

TEMP_DIR=$(mktemp -d)
# deletes the temp directory
function clearTmp {      
    rm -rf "$TEMP_DIR"
    echo "Temp dir [$TEMP_DIR] has been removed"
}

# register the cleanup function to be called on the EXIT signal
trap clearTmp EXIT

git clone ${CONFIG['working-dir']} $TEMP_DIR
cd $TEMP_DIR
git checkout ${CONFIG['branch']}
##########################################################


##########################################################
# Replace plugin icon

iconSearch=$TEMP_DIR/${CONFIG['path-to-icon']}
iconReplace=$LIB_DIR/customIcons/${ICONS["${CONFIG['brand']}"]}
if [[ -f "$iconSearch" && -f "$iconReplace" ]] ; then
    echo -e "\e[33mReplacing plugin icon...\e[0m"
    cp $iconReplace $iconSearch
    echo -e "\e[0;32mOk.\n\e[0m"
fi
##########################################################


##########################################################
# Replace string in files

echo -e "\e[33mReplacing string in files...\e[0m"
for i in "${!REPLACE_IN_FILES[@]}"
do
    search="$i"
    replace="${REPLACE_IN_FILES[$i]}"
    find $TEMP_DIR -not -iwholename '*.git/*' -type f -print0 | xargs -0 -I file_name replace -s "$search" "$replace" -- file_name
done
echo -e "\e[0;32mOk.\n\e[0m"
##########################################################


##########################################################
# Rename files
visitDir() {
    local file=$1
    local origin_file=$2
    local rename_to=$3

    for file in "$1"/*; do
        local isMatch=$(echo $file | grep -o $origin_file)
        local newFile="$file"
        if [ -n "$isMatch" ] ; then
            newFile=$(echo "$file" | sed "s/$origin_file/$rename_to/")
            rename "s/$origin_file/$rename_to/" $file
        fi

        if [ -d "$newFile" ] ; then
            visitDir "$newFile" "$origin_file" "$rename_to";
        fi
    done
}

echo -e "\e[33mRenaming files/directories...\e[0m"
for i in "${!RENAME_FILES[@]}"
do
    origin_file="$i"
    rename_to="${RENAME_FILES[$i]}"
    visitDir $TEMP_DIR $origin_file $rename_to
done
echo -e "\e[0;32mOk.\n\e[0m"
##########################################################


##########################################################
# Commit any changes

echo "Committing changes"
git add .
git commit -q -m "Create brand [${CONFIG['brand']}] repository"
if [ -n "${CONFIG['tag']}" ] ; then
    echo "Tagging commit with tag ${CONFIG['tag']}"
    git tag -f ${CONFIG['tag']}
fi
##########################################################


##########################################################
# Change git remote url

echo -e "\e[44mPlugin ${CONFIG['brand']} will be pushed to the ${CONFIG['git-url']}, branch ${CONFIG['branch']}\e[m"
git remote remove origin
git remote add origin ${CONFIG['git-url']}
git push -u origin master
git push -u origin ${CONFIG['branch']}
git push --tags
##########################################################


echo -e "\e[0;32m\nSuccess.\e[0m"