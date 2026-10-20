# Plugin Installation

## Requirements

- Kanboard 1.2.54 or newer
- PHP 8.2, 8.3, 8.4 or 8.5
- A working Kanboard email transport and background-job runner

## Installation

- **Install via the [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory**
  - _Go to:_
    - Kanboard: `Plugins` &#10562; `Plugin Directory`
  - _or with [PluginManager](https://github.com/aljawaid/PluginManager "A Kanboard plugin") installed:_
    - Kanboard: `Settings` &#10562; `Plugins` &#10562; `Plugin Directory`
- **Install via the [Releases](../master/Releases/ "A copy of each release is saved in the folder") folder**
  - A copy of each release is saved in the `/Releases` folder of the repository
  - Simply extract the `.zip` file into the `/plugins` directory
- **Install via [GitHub](https://github.com/ "Find the correct plugin from the list of repositories")**
  - Download the `.zip` file and decompress everything under the directory `/plugins`
  - The folder inside the `.zip` must not contain any branch names and must be exact case (matching the plugin name)

_Note: The `/plugins` folder is case-sensitive._

- **Install using Git CLI**
  - `git clone` (_or ftp upload_) and extract the `.zip` file into this folder: `.\plugins\` (must be exact case)

## Uninstallation

Kanboard 1.2.54 does not call plug-in cleanup code before deleting plug-in files. Before using Kanboard's Uninstall button:

1. Go to `Settings` &#10562; `Email History`.
2. Review and save the uninstall-data policy.
3. Choose `Prepare for uninstall` and confirm the result.
4. Uninstall the plug-in only after preparation succeeds.

The default policy disables Email History automatic actions and retains their complete configuration for restoration after reinstallation. The optional deletion policy permanently removes the plug-in's active and saved automatic-action records. Deletion is irreversible.

---

Read the full [**Changelog**](../master/changelog.md "See changes") or view the [**README**](../master/README.md "View README")
