AdressBook
==========
5 Entities: <br>
Contact <br>
Address ManyToOne <br>
Email ManyToOne <br>
Phone ManyToOne <br>
ContactGroup ManyToMany <br>

Routes:

"/" <br>
List all contacts in alphabetical order. Input for searching.

"/new" <br>
Adding new contact form, and reception of that form.

"/{id}.{name}" <br>
Display details about single contact. Query optimization with DQL. Auto complete {name} slug. 

"/{id}/modify" <br>
Changing contact data, adding and removing phones, email, addresses, groups. Query optimization with DQL. 

"/{id}/delete" <br>
Reception of delete contact POST request

"/{id}/ [add,delete] /[Address,Phone,Email]" <br>
Reception of POST request from /{id}/modify

"/groups"<br>
List all groups.

"/groups/new"<br>
Creating new group.

"/groups/{id}"<br>
Listing all contacts in group. 

"/groups/{id}/delete"<br>
Reception of delete group POST request
