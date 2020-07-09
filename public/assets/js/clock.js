currentTime();

function getDayName(day){
  var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  return days[day];
}

function getMonthName(month){
  var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  return months[month];
}

function currentTime(){
  var date = new Date(); /* creating object of Date class */
  var hour = date.getHours();
  var min = date.getMinutes();
  var sec = date.getSeconds();

  document.getElementById("time").innerHTML = hour + ":" + min + ":" + sec; /* adding time to the div */
  document.getElementById("date").innerHTML = getDayName(date.getDay()) + "," + " " + date.getDate() + " " + getMonthName(date.getMonth()) + " " + date.getFullYear();

  var t = setTimeout(function(){ currentTime() }, 1000); /* setting timer */

}

function updateTime(k) {
    if (k < 10) {
      return "0" + k;
    }
    else {
      return k;
    }
}
