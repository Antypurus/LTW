<?php if(isset($user)): ?>
  <nav id="menu">
    <ul>
      <li><form action="../list_management/showLists.php" id="searchForm"><input type="text" name="list" id="searchImp" placeholder="Search Tasks"></form></li>
      <li><a href="../list_management/showLists.php?list=completed">Completed</a></li>
      <li><a href="../list_management/showLists.php?list=incomplete">Incomplete</a></li>
      <li><a href="../list_management/showLists.php?list=expiring">Expiring</a></li>
    </ul>
  </nav>

  <p id = "message" class = "hidden"> </p>

  <aside id="lists">
    <h2> To-do lists </h2>
    <table class="lists" id="listsTable">
      <?php
      if($lists!=NULL)
      {
        foreach( $lists as $list)
        {
          $name = strip_tags($list['name']);
          echo '
                  <tr>
                  <td class="id">' . $list['id']. '</td>
                  <td class="name buttonCursor">' . $name. '</td>
                  </tr>
               ';
        }
      }
      ?>
    </table>
        <form id="addListForm" action = "../list_management/addList.php" method="POST">
          <input type="text" maxlength="25" id="listnameID" name="listName" placeholder="List name"><br>
          <input class = "buttonCursor" type="submit" name = "addListButton" value="Add list">
          <input id = "AuthToken" type="hidden" name="AuthenticationToken">
          <input id = "reqID" type="hidden" name="RequestIdentifier">
        </form>
  </aside>

  <?php
    if($lists == null)
    {
      $toHide = "hidden";
    }
    else
      $toHide = " ";
  ?>

  <section id="list">
  <h2 id = "ListName" class = "<?= $toHide ?>"> <?= strip_tags($lists[$index]['name'])?> </h2>
  <div style="overflow-x:auto;" >
  <table class="tasks <?= $toHide ?>" id="taskTable">
  <tbody>
        <?php
          if($tasks != null)
          {
            echo '<tr>
                    <th class="id">ID</th>
                    <th class="status arrowCursor" ></th>
                    <th class="task arrowCursor">Task</th>
                    <th class="expDate arrowCursor">Expiration Date </th>
                    <th id="descriptionHead" class="task arrowCursor">Description </th>
                    <th class="deltete task"></th>
                  </tr>';

        $row = 0;
        foreach( $tasks as $task)
        {
          $taskRow = $task['id'];
          $data = "";
          $diffDay = 0;
          $diffMonth = 0;
          $diffYear = 0;
          if($task['expiring']!=NULL)
          {
            $data = date('d-m-Y', $task['expiring']);
          }

          $title =  strip_tags($task['title']);

          echo '<tr>
          <td class="id verticalTop">' . $task['id']. '</td>';

          if($task['completed'] == "true")
          {
             $checkMark = "&#10004;";
             $htmlstring = '';
             $editTaskString='';

             echo '<td class="status verticalTop">' . $editTaskString . $htmlstring . $checkMark . ' </td>';
          }
          else
          {
             $checkMark = "";
             $htmlstring = '<input type="checkbox" style=" margin-left: -13px; float:right;" onclick="completeTask(this);" id="task' . $taskRow . '/index' . $index .'">';
             $editTaskString='<a class = "buttonCursor left_align" onclick="editTask(this);" id="task' . $taskRow . '"> &#9998;  </a> ';

             echo '<td class="status verticalTop" style="text-align:right">' . $editTaskString . $htmlstring . $checkMark . ' </td>';
          }

          if(!empty($task['title']) && strlen($task['title']) > 26)
            echo '<td><div class = "taskDiv">' . $task['title'] . '</div> </td>';
          else
            echo '<td><div class = "taskDivNotFilled">' . $task['title'] . '</div></td>';

        if($task['expiring'] - time() <= 259200 && $task['completed'] == "false"):
          echo '<td class="expDate closeDate verticalTop"> <b>' . $data . '</b> </td>';
        else:
          echo '<td class="expDate verticalTop"> <b>' . $data . '</b> </td>';
        endif;

        if(!empty($task['description']) && strlen($task['description']) > 26)
          echo '<td><div class = "descriptionDiv">' . $task['description'] . '</div> </td>';
        else
          echo '<td><div class = "descriptionDivNotFilled">' . $task['description'] . '</div></td>';

        if (($pos = strpos($lists[$index]['name'], "-")) !== FALSE)
        {
          echo '</tr>';
        }
        else
        {
          echo' <td class="delete verticalTop">
                <a class = "buttonCursor" onclick="deleteTask(this);" id="task' . $taskRow . '/"> X </a>
                </td>
              </tr>';
        }

        }
      }
      else
        $taskRow = null;
      ?>
  </tbody>
  </table>
</div>
  <br>
    <div style="overflow-x:auto;" class = "<?= $toHide ?>">
      <form id="addTaskForm" action="../task_management/addTask.php" method="POST" onSubmit = "return input()" >
        <input type="text" id="taskNameid" class = "verticalTop" name="taskName" placeholder="task name">
        <input id = "idList2" type="hidden" name = "listID" value = "<?= $lists[$index]['id'] ?>">
        <input id = "taskExpDateInput" class = "verticalTop" type="text" name="taskDate" placeholder="(dd-mm-yyyy)">
        <textarea id= "descriptionBox" rows ="5" name="taskDescription" placeholder = "description (optional)"></textarea> <br>
        <input class = "buttonCursor verticalTop" id="addTaskID" type="submit" name = "addTaskButton" value="Add task"> <br>
      </form>
    </div>
  <br>
  <div class = "<?= $toHide; ?>">
  <form class = " form " action = "../list_management/deleteList.php" method="POST">
    <input class = "buttonCursor" type="submit" name = "deleteListButton" value="Delete list">
    <input id = "idList3" type="hidden"  name = "listID" value = "<?= $lists[$index]['id'] ?>">
  </form>
    <form id="userInviteForm" class = "form" action="../list_management/inviteUsers.php" method="POST">
      <input type="text" id="usernameInput" name="user" placeholder="Search users">
      <input id = "idListName" type="hidden" name = "listName" value = "<?= $lists[$index]['name'] ?>">
      <input id = "idList4" type="hidden"  name = "listID" value = "<?= $lists[$index]['id'] ?>">
      <input id ="inviteToken" type="hidden" name="Token" value="">
      <input class = "buttonCursor" type = "submit" value = "Invite">
    </form>
  </div>

  <form id = "editTaskForm" class = "id" action="../task_management/editTask.php" method = "POST">
      <input type="hidden" id = "editTaskID" name = "taskID">
  </form>

  <script>

  function input()
  {
    inputDate = document.getElementById("taskExpDateInput").value;
    var verifyDateFormat = /^(\d{1,2})-(\d{1,2})-(\d{4})$/;
    var validDateValue = /(^(((0[1-9]|1[0-9]|2[0-8])[-](0[1-9]|1[012]))|((29|30|31)[-](0[13578]|1[02]))|((29|30)[-](0[4,6,9]|11)))[-](19|[2-9][0-9])\d\d$)|(^29[-]02[-](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/;
    var validYear = /(^(\d{1,2})-(\d{1,2})-(19[789]\d|20[01]\d)$)/;
    if (!inputDate.match(verifyDateFormat))
    {
      document.getElementById("message").innerHTML = "Please enter a dd-mm-yyyy date";
      document.getElementById("message").classList.remove('hidden');
      document.getElementById("message").classList.add('error');
      return false;
    }
    else if(!inputDate.match(validDateValue))
    {
      document.getElementById("message").innerHTML = "Please enter a valid date";
      document.getElementById("message").classList.remove('hidden');
      document.getElementById("message").classList.add('error');
      return false;
    }
    else if(!inputDate.match(validYear))
    {
      document.getElementById("message").innerHTML = "Year must be at least 1970";
      document.getElementById("message").classList.remove('hidden');
      document.getElementById("message").classList.add('error');
      return false;
    }
    else
      return true;

    return false;
  }

  function RequestAuthToken(tokenName,elementToChange,isHtml = true)
  {
      var xhttp = new XMLHttpRequest();
       xhttp.onreadystatechange = function()
        {
          if (this.readyState == 4 && this.status == 200)
          {
            if(this.responseText!=-1)
            {
              if(isHtml)
              {
                elementToChange.value = this.responseText;
              }
              else
              {
                elementToChange = this.responseText;
              }
            }
          }
        };

        xhttp.open("POST", "../main/requestsToken.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("&listName=" + tokenName);
    }

  window.onload = function(e)
  {
      let listtableform = document.getElementById("addListForm");
      let tokenVal = listtableform.querySelector("#AuthToken");

      if(tokenVal!=null)
      {
        RequestAuthToken("addListForm",tokenVal);
        let id = listtableform.querySelector("#reqID");
        if(id!=null){
          id.value = "addListForm";
        }
      }
    }

  function XSS_Remove_Tags(string,elementToChange)
  {
    var val = string;
    elementToChange.value = val.replace(/<\/?[^>]+(>|$)/g,"");
  }

  var searchForm = document.querySelector("#searchForm");
  if(searchForm!=null)
  {
    var searchInput = searchForm.querySelector("#searchImp");
    if(searchInput!=null)
    {
      searchInput.oninput = function()
      {
        let str = searchInput.value;
        str = XSS_Remove_Tags(str,searchInput);
      }
    }
  }


  var listTable = document.querySelector("#listsTable");
  var currList = 0;
  function deleteTask(task)
  {
    if (confirm("Are you sure you want to delete this task?") == true)
    {
      var taskID = (task.id.substr(0,task.id.indexOf('/'))).substr(4);
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function()
      {
        if (this.readyState == 4 && this.status == 200)
        {
          if(this.responseText == 0)
          {
              location.reload();
          }
        }
      };

      xhttp.open("POST", "../task_management/deleteTask.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("&task_id=" + taskID);
    }
  }

  function editTask(task)
  {
    let editform = document.getElementById("editTaskForm");
    document.getElementById("editTaskID").value = task.id.substr(4);
    editform.submit();
  }

  var tasklist = [];

  if(listTable!=null)
  {
    let listtableform = document.getElementById("addListForm");
    let formInput = listtableform.querySelector("#listnameID");
    formInput.oninput = function(){
        let str = formInput.value;
        str = XSS_Remove_Tags(str,formInput);
    }

    listTable.onclick = function(ev)
    {
      if(ev.target.parentElement.querySelector('.id')!=null){
      var clickedID = ev.target.parentElement.querySelector('.id').innerText;
      var clickedName = ev.target.parentElement.querySelector('.name').innerText;
      document.getElementById('idList2').value = clickedID;
      document.getElementById('idList3').value = clickedID;
      document.getElementById('idList4').value = clickedID;

      document.getElementById('idListName').value = clickedName;

      var clickedName = ev.target.parentElement.querySelector('.name').innerText;
      document.getElementById('ListName').innerHTML = clickedName;

      var index = ev.target.parentElement.rowIndex;
      if(index==null)
      {
        console.log("NULL row");
      }
      else
      {
        currList = index;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
          if (this.readyState == 4 && this.status == 200)
          {
            if(this.responseText == -1 || this.responseText == -2 || this.responseText == -3)
            {
              document.getElementById("message").innerHTML = "Error";
              document.getElementById("message").classList.remove('hidden');
            }
            else
            {
                var tasks = JSON.parse(this.responseText);
                for(let i=0;i<tasks.length;++i){
                  tasklist.push(JSON.parse(tasks[i]));
                }
              }
            }
            let tableHTML = document.querySelector("#taskTable").querySelector("tbody");
            var htmlString = '';

            if(tasklist.length!=0)
            {
            htmlString = `<tr>
                            <th class="id">ID</th>
                            <th class="status arrowCursor" ></th>
                            <th class="task arrowCursor">Task</th>
                            <th class="expDate arrowCursor">Expiration Date </th>
                            <th id="descriptionHead" class="task arrowCursor">Description </th>
                            <th class="deltete task"></th>
                          </tr>`;
              for(let i=0;i<tasklist.length;++i)
              {
                var taskRow = tasklist[i].id;

                htmlString = htmlString + "\n" + "<tr>";
                htmlString = htmlString + "\n" + '<td class="id verticalTop">' + tasklist[i].id + '</td>';

                if(tasklist[i].completed == "true")
                {
                  var checkMark = "&#10004;";
                  var htmlstring = '';
                  var editTaskString='';

                  htmlString = htmlString + "\n" + '<td class="status verticalTop" >' +
                                editTaskString + htmlstring + checkMark + ' </td>';

                }
                else
                {
                  var checkMark = "";
                  var htmlstring = '<input style=" margin-left: -13px" onclick="completeTask(this);" id="task' + taskRow + '/index' + currList  + '" type="checkbox"';
                  var editTaskString='<a class = "buttonCursor left_align" onclick="editTask(this);" id="task' + taskRow + '"> &#9998;  </a> ';


                  htmlString = htmlString + "\n" + '<td class="status verticalTop" style="text-align:right;" >' +
                                editTaskString + htmlstring + checkMark + ' </td>';
                }

                if((tasklist[i].title).length != 0 && (tasklist[i].title).length > 26)
                  htmlString = htmlString + "\n" + '<td class="buttonCursor" id = "description"> <div class = "taskDiv">' + tasklist[i].title + ' </div></td>';
                else
                  htmlString = htmlString + "\n" + '<td class="buttonCursor" id = "description"><div class = "taskDivNotFilled">' + tasklist[i].title + '</div></td>';

                let data = "";

                if(tasklist[i].expiring!=null)
                {
                  data = tasklist[i].expiring;
                }

                let taskDate = new Date(data* 1000);
                let taskDateYear = taskDate.getYear();
                let taskDateMonth = taskDate.getMonth() + 1;
                let taskDateDay = taskDate.getDate() + 1;

                function pad(n)
                {
                    return (n < 10) ? ("0" + n) : n;
                }

                let currentDate = new Date();
                let currentDay = currentDate.getDate() + 1;
                let currentMonth = currentDate.getMonth() + 1;
                let currentYear = currentDate.getYear();

                let diffData = (currentDate.getTime() - (taskDate.getTime()));
                let diffDay = pad(taskDateDay,2) - pad(currentDay,2);
                let diffMonth = pad(taskDateMonth,2) - pad(currentMonth,2);
                let diffYear = pad(taskDateYear,2) - pad(currentYear,2);

                if(((diffYear < 0 || diffMonth < 0) || (diffYear == 0 && diffMonth == 0 && diffDay <= 3)) && tasklist[i].completed != "true")
                  htmlString = htmlString + "\n" + '<td class="expDate closeDate verticalTop"><b>' + pad(taskDateDay,2) + "-" + pad(taskDateMonth,2) + "-"  + taskDate.getFullYear() +'</td>';
                else
                  htmlString = htmlString + "\n" + '<td class="expDate verticalTop"><b>' + pad(taskDateDay,2) + "-" +  pad(taskDateMonth,2) + "-" + taskDate.getFullYear() +'</td>';

                if((tasklist[i].description).length != 0 && (tasklist[i].description).length > 26)
                  htmlString = htmlString + "\n" + '<td class="buttonCursor" id = "description"> <div class = "descriptionDiv">' + tasklist[i].description + ' </div></td>';
                else
                  htmlString = htmlString + "\n" + '<td class="buttonCursor" id = "description"><div class = "descriptionDivNotFilled">' + tasklist[i].description + '</div></td>';


                  if (clickedName.indexOf('-') > -1)
                  {
                    htmlString = htmlString +  '</tr>';
                  }
                  else
                  {
                    htmlString = htmlString +
                    "\n" +
                    `<td class="delete verticalTop">
                      <a class = "buttonCursor" onclick="deleteTask(this);" id="task` + taskRow + `/"> X </a>
                    </td>`;
                  }

                htmlString = htmlString + "\n" + "</tr>";
              }
            };

            tableHTML.innerHTML = htmlString;
            tasklist.length = 0;
          }
        };

        xhttp.open("POST", "../list_management/getListData.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("index=" + currList);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
          if (this.readyState == 4 && this.status == 200)
          {
            if(this.responseText == 0)
            {
              document.getElementById("message").innerHTML = "Completed Task";
              document.getElementById("message").classList.remove('hidden');
            }
          }
        };

        xhttp.open("POST", "../list_management/getListIndex.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("listID=" + clickedID);
        }
      }
    }

  var inviteUserForm = document.querySelector("#userInviteForm");
  var userNameInput = inviteUserForm.querySelector("#usernameInput");

  userNameInput.oninput = function()
  {
    let str = userNameInput.value;
    str = XSS_Remove_Tags(str,userNameInput);
  }
</script>

<script src='../task_management/completeTask.js'> </script>

</section>
      <?php else: ?>
        <div id="welcome">
            <p>Do it <br>
            Just do it<br>

            Don't let your dreams be dreams<br>
            Yesterday you said tomorrow<br>
            So just do it<br>
            Make your dreams come true<br>
            Just do it<br>

            Some people dream of success<br>
            While you're gonna wake up and work hard at it<br>
            Nothing is impossible<br>

            You should get to the point<br>
            Where anyone else would quit<br>
            And you're not going to stop there<br>
            No, what are you waiting for?<br>

            Do it<br>
            Just do it<br>
            Yes you can<br>
            Just do it<br>
            If you're tired of starting over<br>
            Stop giving up <br> <br>
          - Shia LaBeouf</p>
        </div>
      <?php endif; ?>
