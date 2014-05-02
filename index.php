<HTML><HEAD><TITLE>Template for your web application</TITLE></HEAD>
</HEAD>

Instructions are given in italics.<BR><BR> <i>Your project should
<strong>at least</strong> have the following template and functionality to get t                                                                                                                                  he first 50 points.
If you want to do more fancy stuff, do so only after providing for the
following features:<i><BR><BR> <H1>&lt;Project Name&gt;</H1> <B>Team
Members:</B> &lt;Team-member names&gt;<BR>
<BR>
<hr>
<ul>
<li><B>Relations:</B><BR><BR>

<i>In the following, substitute actual names for Relation1, Relation2
etc. Have entries for five relations in your project, ideally
representing different aspects of the database.
Clicking a link on a relation name should execute an SQL query and list
10-20 tuples in that particular relation (of course, they don't work
below). Your output should be presented on a separate web page in a neat
orderly fashion, one row for each tuple and where columns are evident.
Ensure that all columns have their headers listed and their types are
clear (i.e., state which is an int and which is a char and so
on).</i><BR><BR>
<ol>
<li><a href="http://courses.cs.vt.edu/~cs4604">Relation1</a>
<li><a href="http://courses.cs.vt.edu/~cs4604">Relation2</a>
<li><a href="http://courses.cs.vt.edu/~cs4604">Relation3</a>
<li><a href="http://courses.cs.vt.edu/~cs4604">Relation4</a>
<li><a href="http://courses.cs.vt.edu/~cs4604">Relation5</a>
</ol>
<BR><BR>
<hr>

<li><B>Queries:</B><BR><BR>

<i>In the following, substitute the english query description for a
query to your database. You can use a query from Project Assignment 2 of
the project, if you like, <strong>as long as the query does not take more than 1                                                                                                                                  0
seconds to execute</strong>. This constraint is to prevent overloading the datab                                                                                                                                  ase
with expensive queries that run for minutes or hours. You can use the
<kbd>LIMIT</kbd> keyword to list a small number of tuples, say 10-20,
that satisfy the query. Again, clicking a
link on the query name should execute the appropriate SQL query and list
the tuples that are the answer to that particular query. Make sure your
output is neatly ordered and column names and types are evident. The
output should appear on a separate page.<BR><BR>

<ol>
<li><a href="http://courses.cs.vt.edu/~cs4604">Query1</a>: &lt;here put the engl                                                                                                                                  ish description of that query&gt;
<li><a href="http://courses.cs.vt.edu/~cs4604">Query2</a>: &lt;here put the engl                                                                                                                                  ish description of that query&gt;
<li><a href="http://courses.cs.vt.edu/~cs4604">Query3</a>: &lt;here put the engl                                                                                                                                  ish description of that query&gt;
<li><a href="http://courses.cs.vt.edu/~cs4604">Query4</a>: &lt;here put the engl                                                                                                                                  ish description of that query&gt;
<li><a href="http://courses.cs.vt.edu/~cs4604">Query5</a>: &lt;here put the engl                                                                                                                                  ish description of that query&gt;
</ol>
<BR><BR>
<hr>
<li><B>Ad-hoc Query:</b><BR><BR>
<i>Here, provide a free-form box and two buttons called
"Submit" and "Clear". The intent is that the user can enter any arbitrary
SQL query in the box and click the submit button; The action should be that you                                                                                                                                   should
execute that query on the database and bring up the answers on a separate
page, once again, in a neat orderly fashion. Notice that the input
can be any legal SQL query (permissible under your DB system, of course).
</i><BR><BR>
<FORM METHOD=POST ACTION="">
      <table>
        <tr>
          <td align = right>
            <strong>Please enter your query here<br></font></strong>
          </td>
          <td>
            <input type=text size=30 maxlength=30 name="query">
          </td>
        </tr>
        <tr>
          <td align = right>
            <input type=reset value="Clear">
          </td>
          <td>
            <input type=submit value="Submit">
          </td>
        </tr>
      </table>
    </FORM>
</ul>
</p>
</font>
<HR NOSHADE SIZE=2>
</P>
</BODY></HTML>
