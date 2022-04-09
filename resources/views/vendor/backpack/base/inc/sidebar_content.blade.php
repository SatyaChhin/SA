<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}" id="menu_text"><i class="las la-tachometer-alt"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item nav-dropdown" id="menu_text">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-male"></i> Teacher</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('teacher') }}' id="menu_text"><i
                    class="las la-chalkboard-teacher"></i> TeachersProfile</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('contact') }}'><i
                    class="lar la-address-card"></i> Contacts</a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('student') }}' id="menu_text"><i class="las la-user-friends"></i> Students</a></li>
<li class="nav-item nav-dropdown" id="menu_text">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-list-ul"></i> Category</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('subject') }}' id="menu_text"><i class="lar la-address-book"></i> Subjects</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('group') }}'><i class="las la-users"></i> Groups</a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('classroom') }}' id="menu_text"><i class="lab la-elementor"></i> schedule</a></li>