# KanboardEmailHistory

#### _Automatic Action plugin for [Kanboard](https://github.com/fguillot/kanboard "Kanboard - Kanban Project Management Software")_

This action plugin adds a new action to the Kanboard user workflow process. Upon closing tasks, automatically email the task description and full history of comments to selected recipients.


Features
-------------

- Email the task description and full history of comments to the task creator, assignee or both


Screenshots
----------

**Configure this project**
![Configure this project](../master/Screenshot/usage-1.png "Configure this project")

**Automatic Actions**
![Automatic Actions](../master/Screenshot/usage-2.png "Automatic Actions")

**Send full task description and comments by email**
![Send full task description and comments by email](../master/Screenshot/usage-3.png "Send full task description and comments by email")

**Options are pre-filled**
![Options are pre-filled](../master/Screenshot/usage-4.png "Options are pre-filled")

**Define parameter values**
![Define parameter values](../master/Screenshot/usage-5.png "Define parameter values")


Usage
-------------

Go to `Project` &#10562; `Automatic Actions` &#10562; `Send full task description and comments by email` &#10562; `Parameter values for the action`


Compatibility
-------------

- Requires [Kanboard](https://github.com/fguillot/kanboard "Kanboard - Kanban Project Management Software") ≥`1.2.20`

#### Other Plugins & Action Plugins
- _No known issues_
#### Core Files & Templates
- `01` Template override
- _No database changes_


Changelog
---------

Read the full [**Changelog**](../master/changelog.md "See changes")


Installation
------------

- **Install via the [Kanboard](https://github.com/fguillot/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory**
  - _Go to:_
    - Kanboard: `Plugins` &#10562; `Plugin Directory`
  - _or with [PluginManager](https://github.com/aljawaid/PluginManager) installed:_
    - Kanboard: `Settings` &#10562; `Plugins` &#10562; `Plugin Directory`

**_or_**

- **Install via the [Releases](../master/Releases/ "A copy of each release is saved in the folder") folder**
  - A copy of each release is saved in the `/Releases` folder of the repository
  - Simply extract the `.zip` file into the `/plugins` directory

**_or_**

- **Install via [GitHub](https://github.com/url "Find the correct plugin from the list of repositories")**
  - Download the `.zip` file and decompress everything under the directory `/plugins`
  - The folder inside the `.zip` must not contain any branch names and must be exact case (matching the plugin name)

_Note: The `/plugins` folder is case-sensitive._

**_or_**

- **Install using Git CLI**
  - `git clone` (_or ftp upload_) and extract the `.zip` file into this folder: `.\plugins\` (must be exact case)


Translations
------------

- English (UK), French, German
- _Contributors welcome_


Authors & Contributors
----------------------

- [@aljawaid](https://github.com/aljawaid) - Author
- [Craig Crosby](https://github.com/creecros) - Contributor
- _Contributors welcome_


License
-------
- This project is distributed under the [MIT License](../master/LICENSE "Read The MIT license")
