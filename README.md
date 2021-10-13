# os-ldap2postfix

This plugin for opnsense (at the moment version 21.7) executes a command that can be specified that outputs postmap format and puts that output into the postfix plugin recipients and / or senders table.

The way this works is by using internal and official postfix module APIs which is probably a fairly dirty way of doing it, but without actually integrating this into the official postfix plugin, that's the only way.

The reason why is that some mail servers (Exchange, wink wink, nudge nudge), don't do this reject_unverified_recipient very well, and the best way, if you have many email users, is to get the info directly from AD LDAP.
