<h1 name="user-content-readme-top">KanboardEmailHistory</h1>
<p align="center">
    <a href="https://github.com/aljawaid/KanboardEmailHistory/releases">
        <img src="https://img.shields.io/github/v/release/aljawaid/KanboardEmailHistory?style=for-the-badge&color=brightgreen" alt="GitHub Latest Release (by date)" title="GitHub Latest Release (by date)">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/releases">
        <img src="https://img.shields.io/github/downloads/aljawaid/KanboardEmailHistory/total?style=for-the-badge&color=orange" alt="GitHub All Releases" title="GitHub All Downloads">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/releases">
        <img src="https://img.shields.io/github/directory-file-count/aljawaid/KanboardEmailHistory?style=for-the-badge&color=orange" alt="GitHub Repository File Count" title="GitHub Repository File Count">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/releases">
        <img src="https://img.shields.io/github/repo-size/aljawaid/KanboardEmailHistory?style=for-the-badge&color=orange" alt="GitHub Repository Size" title="GitHub Repository Size">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/releases">
        <img src="https://img.shields.io/github/languages/code-size/aljawaid/KanboardEmailHistory?style=for-the-badge&color=orange" alt="GitHub Code Size" title="GitHub Code Size">
    </a>
</p>
<p align="center">
    <a href="https://github.com/aljawaid/KanboardEmailHistory/discussions">
        <img src="https://img.shields.io/github/discussions/aljawaid/KanboardEmailHistory?style=for-the-badge&color=blue" alt="GitHub Discussions" title="Read Discussions">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/compare">
        <img src="https://img.shields.io/github/commits-since/aljawaid/KanboardEmailHistory/latest?include_prereleases&style=for-the-badge&color=blue" alt="GitHub Commits Since Last Release" title="GitHub Commits Since Last Release">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/compare">
        <img src="https://img.shields.io/github/commit-activity/m/aljawaid/KanboardEmailHistory?style=for-the-badge&color=blue" alt="GitHub Commit Monthly Activity" title="GitHub Commit Monthly Activity">
    </a>
    <a href="https://github.com/kanboard/kanboard" title="Kanboard - Kanban Project Management Software">
        <img src="https://img.shields.io/badge/Plugin%20for-kanboard-D40000?style=for-the-badge&labelColor=000000" alt="Kanboard">
    </a>
</p>

This plugin adds a new Automatic Action to provide any user or department to receive a final email report when a task is closed. Automatic emails are sent detailing the task description including the full comment history. Let this plugin create a digital file copy of your tasks using advanced options including emailing different recipients, adding comment logs and a fallback for a blank email subject line.

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#screenshots">&#8594; Next</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

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

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#features">&#8592; Previous</a>] [<a href="#usage">&#8594; Next</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

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

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#features">&#8592; Previous</a>] [<a href="#installation--compatibility">&#8594; Next</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

## Usage

Go to `Project` &#10562; `Automatic Actions` &#10562; `EmailTaskHistory > Send task description and complete comment history on task closure` &#10562; _Enter parameter values for the action_

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#screenshots">&#8592; Previous</a>] [<a href="#authors--contributors">&#8594; Next</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

## Installation & Compatibility

<p align="left">
    <a href="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/linter.yml">
        <img src="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/linter.yml/badge.svg?branch=master&event=push" alt="Code Scanning" title="View Test">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-7.4.yaml">
        <img src="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-7.4.yaml/badge.svg?branch=master&event=push" alt="PHP Compatibility Test" title="View Test">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-8.0.yaml">
        <img src="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-8.0.yaml/badge.svg?branch=master&event=push" alt="PHP Compatibility Test" title="View Test">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-8.2.yaml">
        <img src="https://github.com/aljawaid/KanboardEmailHistory/actions/workflows/php-compatibility-8.2.yaml/badge.svg?branch=master&event=push" alt="PHP Compatibility Test" title="View Test">
    </a>
</p>

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

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#usage">&#8592; Previous</a>] [<a href="#license">&#8594; Next</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

## Authors & Contributors

- [@aljawaid](https://github.com/aljawaid) - Author
- [Craig Crosby](https://github.com/creecros) - Contributor
- _Contributors welcome_

<p align="right">[<a href="#user-content-readme-bottom">&#8595; Bottom</a>] [<a href="#installation--compatibility">&#8592; Previous</a>] [<a href="#user-content-readme-top">&#8593; Top</a>]</p>

## License

- This project is distributed under the [MIT License](../master/LICENSE "Read The MIT license")

---

<p align="center">
    <a href="https://github.com/aljawaid/KanboardEmailHistory/stargazers" title="View Stargazers">
        <img src="https://img.shields.io/github/stars/aljawaid/KanboardEmailHistory?logo=github&style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/forks" title="See Forks">
        <img src="https://img.shields.io/github/forks/aljawaid/KanboardEmailHistory?logo=github&style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/blob/master/LICENSE" title="Read License">
        <img src="https://img.shields.io/github/license/aljawaid/KanboardEmailHistory?style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/issues" title="Open Issues">
        <img src="https://img.shields.io/github/issues-raw/aljawaid/KanboardEmailHistory?style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/issues?q=is%3Aissue+is%3Aclosed" title="Closed Issues">
        <img src="https://img.shields.io/github/issues-closed/aljawaid/KanboardEmailHistory?style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/discussions" title="Read Discussions">
        <img src="https://img.shields.io/github/discussions/aljawaid/KanboardEmailHistory?style=flat-square" alt="KanboardEmailHistory">
    </a>
    <a href="https://github.com/aljawaid/KanboardEmailHistory/compare/" title="Latest Commits">
        <img alt="GitHub commits since latest release (by date)" src="https://img.shields.io/github/commits-since/aljawaid/KanboardEmailHistory/latest?style=flat-square">
    </a>
</p>
<a name="user-content-readme-bottom"></a>
<p align="right">[<a href="#user-content-readme-top">&#8593; Top</a>]</p>
