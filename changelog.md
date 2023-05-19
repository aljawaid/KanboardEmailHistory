# Changelog


## v2.7

_(most recent changes are listed on top):_
- FIX: Code Syntax - `EmailTaskHistory.php`
- FIX: Code Syntax - `params.php`
- FIX: Code Syntax - `show.php`
- FIX: Code Syntax - `task_create.php`
- FIX: Code Syntax PHP
- FIX: Code Syntax - `changelog.md`
- FIX: Code Syntax - `kanboard-email-history.css`
- NEW: Add Code Test Badges to README.md
- NEW: Add Code Scanning Tests
- FIX: README Navigation Links for GitHub
- FIX: Code Syntax & Line Endings
- FIX: Markdown Syntax
- FIX: Code Syntax & Line Endings
- NEW: Add Footer Badges to README.md
- NEW: Add Section Navigation Links to README.md
- NEW: Add Badges to README.md
- NEW: Add README Page Navigation Links
- FIX: Content Flow for README.md
- Create INSTALL.md


## v2.6

_(most recent changes are listed on top):_
- FIX: Inline Alignment
- FIX: Fallback App Name for ApplicationBranding Plugin
- NEW: Add ApplicationBranding Plugin Compatibility
- FIX: Unix (LF) Line Endings


## v2.5

_(most recent changes are listed on top):_
- NEW: Add Error Message if Email Not Set
- FIX: Blank email subjects when no checkboxes are selected - fixes #22


## v2.0

_(most recent changes are listed on top):_
- Major cleanup of code
- FIX: Disable `error_log` messages by default
- Improve description in form
- Add title for description in form
- Improve translations
- Update README.md
- Update Screenshots
- NEW: Add Help Text Before Parameters - makes the form more user friendly
- Better Buttons in Form
- NEW: Add Help Text for Project Email Address - #17
- NEW: Add Help Text for `project_identifier` - #17
- FIX: Translation Strings
- Add HTML Classes
- Style Form
- NEW: Add Task Status to Comment - mention that the task has been closed (for audit purposes)
- Better Plugin Description
- FIX: Parameter Name
- FIX: Language String
- FIX: Clarify if Empty Subject When Rendered in AutomaticActionUX - **Breaking Change:** renamed `subject` to `email_subject`
- NEW: Add AutomaticActionUX Compatibility - fixes #20 Format Related Form Fields in Rendered Form in `action/index.php`
- FIX: Better Solution for HTML `label` Element Usage - default code is not compatible with screenreaders and keyboards as the label ID was wrong for the relevant parameter
- FIX: Replace Blank Subjects with Default Subject
- Rename `Task Activity Report` Wording
- Add form help for subject
- Make Selected Options Italic
- Better Wording for Label Translations
- Change Parameter Form Title String
- FIX: Add Translation for `send_to`
- Add CSS File for Styling
- FIX: Remove unnecessary `label` for checkbox - becomes the same word for multiple checkboxes
- Add CSS Class to checkbox
- Add translations for placeholder
- NEW: Override Form Parameters Without Affecting Other Plugins - thanks @creecros
- FIX: Parameter Name - fixes Bug: `project_identifier` being recognised as `project_id` parameter #16
- FIX: Autocomplete on form
- FIX: Add Placeholder for Email Subject
- FIX: PHP Syntax
- Add Function to Controller for Action Parameter Workaround
- FIX: Form Controller for Action Parameter Workaround
- Add Controller for Action Parameter Workaround
- Add Form for Action Parameter Workaround
- Add Template Core File for Action Parameter Workaround
- Add Template Override for Action Parameter Workaround
- Remove Autosubtasks for Workaround Solution
- FIX: Email Subject Not Detecting `project_name` or `project_identifier` - thanks @creecros - fixes #14
- Better Naming of Parameters
- Update HTML
- FIX: Account for `project_email` with `assignee` or `creator`
- FIX: `project_email` Does Nothing - fixes #13 Bug: `project_email` Does Nothing
- Better error log messages
- Code Cleanup
- NEW: Add Translation Starter Template - fixes #9
- Update `fr_FR` translations
- Update `de_DE` translations
- Reset `de_DE` and `fr_FR` translations
- Update `en_GB` translations
- Update Plugin Description
- FIX: Code Translations
- Change Action Dropdown Description - better wording
- Change List Order
- FIX: Try to account for `assignee`/`creator` with `project_email`
- NEW: Add More Recipients - fixes #4
- Change License to MIT
- UPDATE: Readme with Usage Instructions

**Note: Any Automatic Actions created before v2.0 will need to be re-created due to the email subject parameter needing to be more specific for cross plugin compatibility**


## v1.2

_(most recent changes are listed on top):_
- FIX: Plugin Version


## v1.1

_(most recent changes are listed on top):_
- FIX: Plugin Name (was action name)
- FIX: Rename Release version filename
- Add Releases folder


## v1.0

_(most recent changes are listed on top):_
- Initial release - thanks @creecros
- Cleanup code
- Update README.md
- NEW: Add Translations - added French, German (used Google Translate for translations)
- NEW: Added `en_GB` translations
- Rename EmailFullTaskHistory to EmailTaskHistory

---

Read the full [**Changelog**](../master/changelog.md "See changes") or view the [**README**](../master/README.md "View README")
