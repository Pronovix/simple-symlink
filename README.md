# Simple Symlink

Composer script that symlinks files and directories.

## Example usage

```shell
$ composer require pronovix/simple-symlink
```

```json
      "extra": {
        "simple-symlinks": {
            ".": "build/web/modules/drupal_module"
        }
    },
     "scripts": {
        "post-install-cmd": [
            "Pronovix\\SimpleSymlink\\ScriptHandler::createSymlinks"
        ],
        "post-update-cmd": [
            "Pronovix\\SimpleSymlink\\ScriptHandler::createSymlinks"
        ]
    }
```

This ensures that the Composer root project is symlinked to the `build/web/modules/drupal_module` directory when a
package is installed or updated.
