# KanboardEmailHistory
![GitHub All Releases](https://img.shields.io/github/downloads/aljawaid/KanboardEmailHistory/total?style=for-the-badge "GitHub All Downloads") - **_An Automatic Action plugin for [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software")_**

This plugin adds a new Automatic Action to provide any user or department to receive a final email report when a task is closed. Automatic emails are sent detailing the task description including the full comment history. Let this plugin create a digital file copy of your tasks using advanced options including emailing different recipients, adding comment logs and a fallback for a blank email subject line.


## Features

- Create unique subjects for different types of projects or tasks
- Works with Kanboard background jobs
- Improved user friendly form
- Display the Automatic Action description before proceeding to the options
- Fallback for blank subjects to avoid email spam detection
- Options to build the email subject line to your requirements

**Email Recipients**
- Task Assignee
- Task Creator
- Task Assignee and Task Creator
- Task Assignee and Project Email Address
- Task Creator and Project Email Address
- Project Email Address
- All of the above

**Email Subjects** - _Examples_
- `TASK TITLE` only - _No options selected, fallback subject line)_
- `TASK TITLE` only
- `PROJECT NAME` only
- `PROJECT IDENTIFIER` only
- `PROJECT IDENTIFIER` + `TASK TITLE`
- `PROJECT NAME` + `PROJECT IDENTIFIER`
- `PROJECT NAME` + `TASK TITLE`
- `PROJECT NAME` + `PROJECT IDENTIFIER` + `TASK TITLE`


## Screenshots

**Configure this project**

![Configure this project](../master/Screenshots/usage-1.png "Configure this project")

**Automatic Actions**

![Automatic Actions](../master/Screenshots/usage-2.png "Automatic Actions")

**EmailTaskHistory > Send task description and complete comment history on task closure**

![EmailTaskHistory > Send task description and complete comment history on task closure](../master/Screenshots/usage-3.png "EmailTaskHistory > Send task description and complete comment history on task closure")

**Options are pre-filled in an improved user-friendly form**

![Options are pre-filled](../master/Screenshots/usage-4.png "Options are pre-filled")

**Options for this Automatic Action**

![Define parameter values](../master/Screenshots/usage-5.png "Define parameter values")

**Automatic Action listed in the Project Settings** _- compatible with [AutomaticActionUX](https://github.com/aljawaid/AutomaticActionUX)_

![Project Settings](../master/Screenshots/usage-6.png "Project Settings")

**Error Messages** _- compatible with [AutomaticActionUX](https://github.com/aljawaid/AutomaticActionUX)_

![Error Messages](../master/Screenshots/error-messages.png "Error Messages")


## Usage

Go to `Project` &#10562; `Automatic Actions` &#10562; `EmailTaskHistory > Send task description and complete comment history on task closure` &#10562; _Enter parameter values for the action_


## Installation & Compatibility

<details>
    <summary><strong>Installation</strong></summary>

- Install via the **[Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory** or see [INSTALL.md](../master/INSTALL.md)
- Read the full [**Changelog**](../master/changelog.md "See changes") to see the latest updates

</details>
<details>
    <summary><strong>Compatibility</strong></summary>

- Requires [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") ≥`1.2.20`
- **Other Plugins & Action Plugins**
  - _No known issues_
  - Compatible with [AutomaticActionUX](https://github.com/aljawaid/AutomaticActionUX), [AutoSubtasks](https://github.com/creecros/AutoSubtasks), [ApplicationBranding](https://github.com/aljawaid/ApplicationBranding)
- **Core Files & Templates**
  - `01` Template override
  - _No database changes_

</details>
<details>
    <summary><strong>Translations</strong></summary>

- English (UK), French, German
- _Starter template available_

</details>


## Authors & Contributors

- [@aljawaid](https://github.com/aljawaid) - Author
- [Craig Crosby](https://github.com/creecros) - Contributor
- _Contributors welcome_


## License

- This project is distributed under the [MIT License](../master/LICENSE "Read The MIT license")
