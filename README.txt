README for boaidp, version 2.0.3
July 3th, 2013

This is the new implementation of Moodle's Block for metadata 
associated to Moodle's Courses and their further manipulation (in a manually mode, by editing course's block instances).
This new version are thought to customize (via GLOBAL BLOCK'S SETTING) your DCMES fields which can be filled by means block instance configuration.
Dynamic creation of metadata schema are made each time your course block instance is edited. 
No additional tables. No preload fields values (only Moodle config is used).

The main aims of these metadata is to serve as basis (Data Provider) for further OAI-PMH 2.0 Harvester implementations, so feel you free to test and improve this Moodle's Block Plugin.

Besides, inside phpoai2 folder, we have customized and PHP OAI-PMH interface for query de metadata associated with each BOAIDP course's block.
For testing OAI-PMH Interface please use the following URL: http://<moodlehost>/blocks/boaidp/phpoai2/oai2.php

This Moodle's Block is written in PHP, following the Moodle Development/Contributions guidelines/recommendations.


GETTING AND COLLABORATING ON GITHUB
https://github.com/mglaredo/moodle-block_boaidp

GETTING STABLE PACKAGES ON MOODLE
https://moodle.org/plugins/view.php?plugin=block_boaidp
