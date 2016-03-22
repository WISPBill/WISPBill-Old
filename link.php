<?php
echo'
<!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview">
          <a href="#"><i class="fa fa-compass"></i> <span>Lead Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          <li class="treeview">
          <a href="#"><i class="fa fa-code-fork"></i> <span>Workflows</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="convertlead.php?workflow=lead1">Lead to Active Customer</a></li>           
          </ul></li>
            <li><a href="mapleads.php">Map Lead\'s</a></li>
            <li><a href="viewlead.php">View Lead\'s</a></li>
            <li><a href="createlead.php">Create a Lead</a></li>
            <li><a href="convertleadin.php">Convert Lead to An Install</a></li>
            <li><a href="convertlead.php">Convert Lead to An Account</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Customer Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Edit Customer\'s</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="billcustomer.php">Change Billing Info</a></li>
            <li><a href="emailcustomer.php">Change Email</a></li>
            <li><a href="phonecustomer.php">Change Phone Number</a></li>
            <li><a href="changecusser.php">Change Service Plan</a></li>
          </ul></li>
            <li><a href="viewcustomer.php">View Customer\'s</a></li>
            <li><a href="mapcus.php">Map Customer\'s</a></li>
            <li><a href="setbill.php">Set Billing Info</a></li>
            <li><a href="activatecustomer.php">Activate A Customer</a></li>
            <li><a href="linkcusdevice.php">Link Device to Customer</a></li>
            <li><a href="ipcustomer.php">Assign Static IP Address</a></li>
            <li><a href="ipdelete.php">Delete Static IP Assignments </a></li>
            <li><a href="deletecustomer.php">Delete Customer</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#"><i class="fa fa-life-ring"></i> <span>Customer Support</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="treeview">
          <a href="#"><i class="fa fa-ticket"></i> <span>Ticket Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="assignticket.php">Assign Tickets</a></li>
            <li><a href="viewyourticket.php">View Your Tickets</a></li>
            <li><a href="createticketnote.php">Create a Ticket Note</a></li>
            <li><a href="closeticket.php">Close a Ticket</a></li>
          </ul></li>
            <li><a href="troubleshooting.php">Troubleshooting</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-plug"></i> <span>Device Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          <li class="treeview">
          <a href="#"><i class="fa fa-server"></i> <span>Router Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          <li class="treeview">
          <a href="#"><i class="fa fa-fire"></i> <span>Firewall Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="setupacl.php">Set up ACL Firewall</a></li>
            <li><a href="viewfirewall.php">View Firewalls</a></li>
          </ul></li>
          <li><a href="configrouter.php">Configure Monitoring</a></li>
          </ul>
          <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Link Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="addlink.php">Add a Link</a></li>
            <li><a href="viewlinks.php">View Links</a></li>
            <li><a href="maplinks.php">Map Links</a></li>
          </ul></li>
          <li class="treeview">
          <a href="#"><i class="fa fa-stethoscope"></i> <span>Network Monitoring</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
           <li><a href="addnetwork.php">Add a Network</a></li>
           <li><a href="viewdown.php">View Down Devices</a></li>
          </ul></li>
            <li><a href="createdevice.php">Add a Device to Inventory</a></li>
            <li><a href="viewdeviceinventory.php">View Device Inventory</a></li>
            <li><a href="ipview.php">View Static IP Assignments </a></li>
            <li><a href="adddhcp.php">Add a DHCP Server</a></li>
            <li><a href="viewdhcp.php">View DHCP Servers</a></li>
             <li><a href="linkdevice.php">Link a Device to LibreNMS</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-map"></i> <span>Site Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Contact Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="createcontact.php">Add a Contact</a></li>
            <li><a href="viewcontact.php">View Contacts</a></li>
            <li><a href="createcontactnote.php">Create a Note about a Contact</a></li>
            <li><a href="viewcontactnotes.php">View Contact\'s Notes</a></li>
          </ul></li>
            <li><a href="createsite.php">Create a Site</a></li>
            <li><a href="mapsites.php">Map Sites</a></li>
            <li><a href="maplinks.php">Map Links</a></li>
            <li><a href="mapdump.php">Map Everything</a></li>
            <li><a href="drawcova.php">Draw Coverage</a></li>
            <li><a href="mapcov.php">Map Coverage</a></li>
            <li><a href="linkgear.php">Link a Device to a Site</a></li>
            <li><a href="linkrouter.php">Link a Router to a Site</a></li>
            <li><a href="configsite.php">Initial Configuration (LibreNMS)</a></li>
          </ul>
        </li>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-cart-plus"></i> <span>Service Plans</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="createplan.php">Create a Service Plan</a></li>
            <li><a href="viewplan.php">View Service Plans</a></li>
            <li><a href="speedplan.php">Change a Service Plan\'s Speed</a></li>
            <li><a href="deleteplan.php">Delete a Service Plan</a></li> 
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-calendar-plus-o"></i> <span>Schedule Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="drivetime.php">Estimate Drive Time</a></li>
            <li><a href="sethours.php">Set Work Schedule</a></li>
            <li><a href="edithours.php">Edit Work Schedule</a></li>
            <li><a href="scheduletask.php">Schedule an Existing Task</a></li>
            <li><a href="starttask.php">Start A Task</a></li>
           <li><a href="endtask.php">End A Task</a></li>
          </ul>
        </li>
        <li>
          <a href="calendar.php">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <small class="label pull-right bg-green">';
            echo "$calevents</small>
          </a>
        </li>
       
          ";
            if($emailreader == true){
                echo" <li>
                <a href='mailbox.php'>
            <i class='fa fa-envelope'></i> <span>Mailbox</span>
            <small class='label pull-right bg-blue'>$mailevents</small>
          </a>";
            }else{
            //Email it not on
          }
          echo '
        </li>
          <li class="treeview">
          <a href="#"><i class="fa fa-database"></i> <span>Data Views</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="viewportdata.php">View Router Port Data</a></li>
            <li><a href="viewavgcpedata.php">View Average CPE Preformance</a></li>
           
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>';
  ?>