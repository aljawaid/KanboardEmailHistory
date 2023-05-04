<?php

return array(
    //
    // GENERAL
    //
    'This plugin adds a new Automatic Action to provide any user or department to receive a final email report when a task is closed. Automatic emails are sent detailing the task description including the full comment history. Let this plugin create a digital file copy of your tasks using advanced options including emailing different recipients, adding comment logs and a fallback for a blank email subject line.' => 'Ce plugin ajoute une nouvelle action automatique pour permettre à tout utilisateur ou service de recevoir un rapport final par e-mail lorsqu\'une tâche est fermée. Des e-mails automatiques sont envoyés détaillant la description de la tâche, y compris l\'historique complet des commentaires. Laissez ce plugin créer une copie de fichier numérique de vos tâches à l\'aide d\'options avancées, notamment l\'envoi d\'e-mails à différents destinataires, l\'ajout de journaux de commentaires et une solution de secours pour une ligne d\'objet d\'e-mail vide.',
    //
    // Action/EmailTaskHistory.php
    //
    'EmailTaskHistory > Send task description and complete comment history on task closure' => 'EmailTaskHistory > Envoyer la description de la tâche et l\'historique complet des commentaires lors de la fermeture de la tâche',
    'Email Subject' => 'Sujet du Courriel',
    'Send to Assignee' => 'Envoyer au Cessionnaire',
    'Send to Creator' => 'Envoyer au Créateur',
    'Assignee & Creator' => 'Cessionnaire & Créateur',
    'Assignee & Project Email' => 'Courriel du Cessionnaire et du Projet',
    'Creator & Project Email' => 'Courriel du Créateur et du Projet',
    'Project Email' => 'Courriel du Projet',
    'Assignee, Creator & Project Email' => 'Courriel du Cessionnaire, du Créateur et du Projet',
    'Include the Task Title and ID in subject line?' => 'Inclure le titre et l\'ID de la tâche dans la ligne d\'objet?',
    'Include the Project Name in the subject line?' => 'Inclure le nom du projet dans la ligne d\'objet?',
    'Include the Project Identifier in the subject line?' => 'Inclure l\'identifiant du projet dans la ligne d\'objet?',
    'TASK CLOSED: A copy of the task history has been emailed to @%s (Task Assignee) [Subject: %s]' => 'TÂCHE FERMÉE: Une copie de l\'historique de la tâche a été envoyée par e-mail à @%s (chargé de la tâche) [Sujet: %s]',
    'TASK CLOSED: A copy of the task history has been emailed to @%s (Task Creator) [Subject: %s]' => 'TÂCHE FERMÉE: Une copie de l\'historique des tâches a été envoyée par e-mail à @%s (Créateur de tâches) [Sujet: %s]',
    'TASK CLOSED: A copy of the task history has been emailed to the project email address [Subject: %s]' => 'TÂCHE FERMÉE: Une copie de l\'historique des tâches a été envoyée par e-mail à l\'adresse e-mail du projet [Sujet: %s]',
    //
    // action_creation/params.php
    //
    'Define Automatic Action Parameters' => 'Définir les paramètres d\'action automatique',
    'Selected Action' => 'Action sélectionnée',
    'Selected Event' => 'Événement Sélectionné',
    'Options' => 'Choix',
    'Activity Report' => 'Rapport d\'activité de tâche',
    'Email Recipient(s)' => 'Destinataire(s) de l\'e-mail',
    'If left blank then "Activity Report" is used as the subject' => 'Si laissé vide, "Rapport d\'activité de tâche" est utilisé comme sujet',
    //
    // comment/show.php
    //
    'Created at:' => 'Créé à:',
    'Local Time' => 'Heure Locale',
    'LT' => 'HL',
    'Updated at:' => 'Mis à jour à:',
    'ID: ' => 'IDENTIFIANT:',
    //
    // notification/footer.php
    //
    'My Workspace' => 'Mon espace de travail',
    'System Generated Email' => 'E-mail généré par le système',
    'View Task' => 'Afficher la tâche',
    'View Board' => 'Voir le tableau',
    //
    // notification/task_create.php
    //
    'Summary' => 'Résumé',
    'Task Status:' => 'État de la tâche:',
    'Task N°:' => 'Tâche N°:',
    'Project:' => 'Projet:',
    'Priority:' => 'Priorité:',
    'Reference:' => 'Référence:',
    'Complexity:' => 'Complexité:',
    'Created:' => 'Créé:',
    'Started:' => 'A débuté:',
    'Modified:' => 'Modifié:',
    'Due Date:' => 'Date d\'échéance:',
    'Completed:' => 'Complété:',
    'Created by %s' => 'Créé par %s',
    'Assigned to %s' => 'Affecté à %s',
    'Nobody was assigned to this task' => 'Personne n\'a été affecté à cette tâche',
    'Board Column:' => 'Colonne du conseil:',
    'Swimlane:' => 'Couloir:',
    'Category:' => 'Catégorie:',
    'Task Position:' => 'Poste de travail:',
    'Recurrence:' => 'Récurrence:',
    'Recurrent task is scheduled to be generated' => 'La tâche récurrente doit être générée',
    'Recurrent task has been generated' => 'La tâche récurrente a été générée',
    'This task was created by: ' => 'Cette tâche a été créée par: ',
    'This task has created this child task: ' => 'Cette tâche a créé cette tâche enfant: ',
    'Description' => 'La description',
    //
    // task_comments/show.php
    //
    'Comments' => 'Commentaires',
    'No comments' => 'Sans commentaires',
    //
    // action_creation/event.php
    //
    'Select an event for this Automatic Action' => 'Sélectionnez un événement pour cette action automatique',
    'Event' => 'Événement',
    'When the selected event occurs execute the corresponding action' => 'Lorsque l\'événement sélectionné se produit, exécutez l\'action correspondante',
    'Description' => 'La description',
    'This Automatic Action emails a final report once the task is closed. Emails are sent individually detailing the task description and full comment history.' => 'Cette action automatique envoie un rapport final par e-mail une fois la tâche fermée. Les e-mails sont envoyés individuellement en détaillant la description de la tâche et l\'historique complet des commentaires.',
    'A comment is automatically added to the task for each successful email sent.' => 'Un commentaire est automatiquement ajouté à la tâche pour chaque e-mail envoyé avec succès.',
);
