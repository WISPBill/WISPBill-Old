<?php
echo'
<!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview">
          <a href="#"><i class="fa fa-compass"></i> <span>Lead Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="mapleads.php">Map Leads</a></li>
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
          </ul>
            <li><a href="viewcustomer.php">View Customer\'s</a></li>
            <li><a href="mapcus.php">Map Customer\'s</a></li>
            <li><a href="setbill.php">Set Billing Info</a></li>
            <li><a href="linkcusdevice.php">Link Device to Customer</a></li>
            <li><a href="ipcustomer.php">Assign Static IP Address</a></li>
            <li><a href="ipdelete.php">Delete Static IP Assignments </a></li>
            <li><a href="deletecustomer.php">Delete Customer</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#"><i class="fa fa-life-ring"></i> <span>Customer Support</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-server"></i> <span>Device Management</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="createdevice.php">Add a Device to Inventory</a></li>
            <li><a href="ipview.php">View Static IP Assignments </a></li>
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
          </ul>
            <li><a href="createsite.php">Create a Site</a></li>
            <li><a href="mapsites.php">Map Sites</a></li>
            <li><a href="drawcova.php">Draw Coverage</a></li>
            <li><a href="mapcov.php">Map Coverage</a></li>
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
        <li>
          <a href="">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <small class="label pull-right bg-green">';
            echo "$calevents</small>
          </a>
        </li>
        <li>
          <a href=''>
            <i class='fa fa-envelope'></i> <span>Mailbox</span>
            <small class='label pull-right bg-blue'>$mailevents</small>
          </a>";
          echo '
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>';
  ?>