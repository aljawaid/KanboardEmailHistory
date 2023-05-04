<?php

return array(
    //
    // GENERAL
    //
    'This plugin adds a new Automatic Action to provide any user or department to receive a final email report when a task is closed. Automatic emails are sent detailing the task description including the full comment history. Let this plugin create a digital file copy of your tasks using advanced options including emailing different recipients, adding comment logs and a fallback for a blank email subject line.' => 'Dieses Plugin fügt eine neue automatische Aktion hinzu, um jedem Benutzer oder jeder Abteilung zu ermöglichen, einen abschließenden E-Mail-Bericht zu erhalten, wenn eine Aufgabe geschlossen wird. Es werden automatische E-Mails mit einer detaillierten Aufgabenbeschreibung einschließlich des vollständigen Kommentarverlaufs gesendet. Lassen Sie dieses Plugin eine digitale Dateikopie Ihrer Aufgaben erstellen, indem Sie erweiterte Optionen verwenden, darunter das Versenden von E-Mails an verschiedene Empfänger, das Hinzufügen von Kommentarprotokollen und ein Fallback für eine leere E-Mail-Betreffzeile.',
    //
    // Action/EmailTaskHistory.php
    //
    'EmailTaskHistory > Send task description and complete comment history on task closure' => 'EmailTaskHistory > Aufgabenbeschreibung und vollständigen Kommentarverlauf beim Aufgabenabschluss senden',
    'Email Subject' => 'E-Mail Betreff',
    'Send to Assignee' => 'An Zuständigen senden',
    'Send to Creator' => 'An Ersteller senden',
    'Assignee & Creator' => 'Beauftragter & Schöpfer',
    'Assignee & Project Email' => 'Beauftragter & Projekt-E-Mail',
    'Creator & Project Email' => 'Ersteller- und Projekt-E-Mail',
    'Project Email' => 'Projekt-E-Mail',
    'Assignee, Creator & Project Email' => 'Empfänger, Ersteller und Projekt-E-Mail',
    'Include the Task Title and ID in subject line?' => 'Den Aufgabentitel und die ID in die Betreffzeile aufnehmen?',
    'Include the Project Name in the subject line?' => 'Den Projektnamen in die Betreffzeile aufnehmen?',
    'Include the Project Identifier in the subject line?' => 'Die Projektkennung in die Betreffzeile aufnehmen?',
    'TASK CLOSED: A copy of the task history has been emailed to @%s (Task Assignee) [Subject: %s]' => 'AUFGABE GESCHLOSSEN: Eine Kopie des Aufgabenverlaufs wurde per E-Mail an @%s (Aufgabenverantwortlicher) [Gegenstand: %s] gesendet.',
    'TASK CLOSED: A copy of the task history has been emailed to @%s (Task Creator) [Subject: %s]' => 'AUFGABE GESCHLOSSEN: Eine Kopie des Aufgabenverlaufs wurde per E-Mail an @%s (Aufgabenersteller) [Gegenstand: %s] gesendet.',
    'TASK CLOSED: A copy of the task history has been emailed to the project email address [Subject: %s]' => 'AUFGABE GESCHLOSSEN: Eine Kopie des Aufgabenverlaufs wurde per E-Mail an die E-Mail-Adresse des Projekts [Gegenstand: %s] gesendet',
    //
    // action_creation/params.php
    //
    'Define Automatic Action Parameters' => 'Definieren Sie automatische Aktionsparameter',
    'Selected Action' => 'Ausgewählte Aktion',
    'Selected Event' => 'Ausgewähltes Ereignis',
    'Options' => 'Optionen',
    'Activity Report' => 'Aufgabenaktivitätsbericht',
    'Email Recipient(s)' => 'E-mail Empfänger',
    'If left blank then "Activity Report" is used as the subject' => 'Wenn das Feld leer gelassen wird, wird "Aufgabenaktivitätsbericht" als Betreff verwendet',
    //
    // comment/show.php
    //
    'Created at:' => 'Hergestellt in:',
    'Local Time' => 'Ortszeit',
    'LT' => 'OZ',
    'Updated at:' => 'Aktualisiert am:',
    'ID: ' => 'ICH WÜRDE: ',
    //
    // notification/footer.php
    //
    'My Workspace' => 'Mein Arbeitsbereich',
    'System Generated Email' => 'Vom System generierte E-Mail',
    'View Task' => 'Aufgabe anzeigen',
    'View Board' => 'Vorstand ansehen',
    //
    // notification/task_create.php
    //
    'Summary' => 'Zusammenfassung',
    'Task Status:' => 'Aufgabenstatus:',
    'Task N°:' => 'Aufgabe Nr.:',
    'Project:' => 'Projekt:',
    'Priority:' => 'Priorität:',
    'Reference:' => 'Bezug:',
    'Complexity:' => 'Komplexität:',
    'Created:' => 'Erstellt:',
    'Started:' => 'Gestartet:',
    'Modified:' => 'Geändert:',
    'Due Date:' => 'Geburtstermin:',
    'Completed:' => 'Vollendet:',
    'Created by %s' => 'Erstellt von %s',
    'Assigned to %s' => '%s zugewiesen',
    'Nobody was assigned to this task' => 'Niemand wurde mit dieser Aufgabe beauftragt',
    'Board Column:' => 'Board-Spalte:',
    'Swimlane:' => 'Schwimmbahn:',
    'Category:' => 'Kategorie:',
    'Task Position:' => 'Aufgabe Position:',
    'Recurrence:' => 'Wiederauftreten:',
    'Recurrent task is scheduled to be generated' => 'Wiederkehrende Aufgabe soll generiert werden',
    'Recurrent task has been generated' => 'Wiederkehrende Aufgabe wurde generiert',
    'This task was created by: ' => 'Diese Aufgabe wurde erstellt von: ',
    'This task has created this child task: ' => 'Diese Aufgabe hat diese untergeordnete Aufgabe erstellt: ',
    'Description' => 'Beschreibung',
    //
    // task_comments/show.php
    //
    'Comments' => 'Bemerkungen',
    'No comments' => 'Keine Kommentare',
    //
    // action_creation/event.php
    //
    'Select an event for this Automatic Action' => 'Wählen Sie ein Ereignis für diese automatische Aktion aus',
    'Event' => 'Ereignis',
    'When the selected event occurs execute the corresponding action' => 'Wenn das ausgewählte Ereignis eintritt, führen Sie die entsprechende Aktion aus',
    'Description' => 'Beschreibung',
    'This Automatic Action emails a final report once the task is closed. Emails are sent individually detailing the task description and full comment history.' => 'Diese automatische Aktion sendet einen Abschlussbericht per E-Mail, sobald die Aufgabe abgeschlossen ist. E-Mails werden einzeln gesendet, die die Aufgabenbeschreibung und den vollständigen Kommentarverlauf enthalten.',
    'A comment is automatically added to the task for each successful email sent.' => 'Für jede erfolgreich gesendete E-Mail wird der Aufgabe automatisch ein Kommentar hinzugefügt.',
);
