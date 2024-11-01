// JavaScript Document
// ***********************************************
// AUTHOR: WWW.CGISCRIPT.NET, LLC
// URL: http://www.cgiscript.net
// Use the script, just leave this message intact.
// Download your FREE CGI/Perl Scripts today!
// ( http://www.cgiscript.net/scripts.htm )
// ***********************************************
      var now = new Date();
      var days = new Array(
        'Sunday','Monday','Tuesday',
        'Wednesday','Thursday','Friday','Saturday');
      var months = new Array(
        'January','February','March','April','May',
        'June','July','August','September','October',
        'November','December');
      var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();
        function fourdigits(number)	{
          return (number < 1000) ? number + 1900 : number;}
      today =  months[now.getMonth()] + " " +
    date + ", " +
    (fourdigits(now.getYear()));
      document.write(today);

