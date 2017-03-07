# WordPress Plugin Movies

The repository contains simple WordPress Plugin which adds a new custom post type "movie".
- Each movie has one custom field "rating".
- Each movie has a "noindex nofollow" tag on its head.
- On the frontend page of each movie post, there is 5 empty stars at the bottom.
A user is able to give the movie a rating by marking how many stars he would like (the stars are filled with yellow).
The rating is set using an AJAX request, no page refresh happen when saving the value to the Database.

\* One movie has one rating

\* Design is very simple