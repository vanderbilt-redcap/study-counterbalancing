# Study Counterbalancing
Method to provide study counterbalancing for participants. Meant to help prevent participants being influenced by questions being asked in a specific order every time. This is achieved by randomizing the order of the surveys for each participant. The possible outcomes of survey order are balanced so that no one survey order appears more often than another.

### Explanation of module settings:
**"Surveys Grouped in Counterbalancing"** - All of the settings in the module are grouped together under this heading. It can be repeated in the case of multiple sets of surveys to counterbalance, or multiple events in the REDCap project.<br>
**"Form to Complete to Randomize Participant"** - This setting will be a dropdown of the forms present in the REDCap project. It will indicate the survey that needs to be completed in order to begin the counterbalancing process. This survey should be one that needs to be filled out immediately before all of the counterbalanced surveys for every participant.<br>
**"Event to Apply This Counterbalancing To"** - A dropdown selection of the events in the REDCap project. For longitudinal projects, this must be selected to determine what event is being counterbalanced. This means that every event needs to be counterbalanced individually.<br>
**"List of Forms to be Included in Counterbalancing"** - The heading for a set of repeatable settings that indicate all of the surveys that should be included in the counterbalancing.<br>
**"Form/Survey To Counterbalance"** - Dropdown to select the survey that should be in the counterbalancing. It does not matter what order surveys are listed in these settings.<br>
**"View Form as a Survey?"** - This determines whether you want the form to be completed as a survey or a plain data entry form. This will affect how the user is redirected from the previous form.

### Viewing Counterbalancing for Participants
In the left toolbar, there is a link titled "Display Participant Counterbalancing". This page displays a table listing all participants in the REDCap project, according to their record ID, along with the order that their surveys have been counterbalanced. This can be useful if needed for analysis of participant results compared to their survey ordering. It can also be useful in case a participant had to stop participating in the middle of their survey listing, and needs to be able to enter back into their latest incomplete survey. Knowing the order of their counterbalancing can allow you to provide them the link back into their next survey if needed.  