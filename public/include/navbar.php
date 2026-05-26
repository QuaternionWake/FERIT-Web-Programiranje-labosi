<nav aria-label="Main">
	<svg id="menu-toggle" width="55" height="55">
		<rect x="10" y="10" width="35" height="5" fill="white"/>
		<rect x="10" y="25" width="35" height="5" fill="white"/>
		<rect x="10" y="40" width="35" height="5" fill="white"/>
	</svg>
	<ul>
		<li><a href="/index.php">Početna</a></li>
		<li><a href="/grafikon.php">Grafikon</a></li>
		<li><a href="/slike.php">Slike</a></li>
	</ul>
	<div class="login">
		<?php if ($is_logged_in): ?>
			<ul>
				<li><a href="/add-song.php">Add song</a></li>
				<li><a href="/logout.php">Logout</a></li>
				<li><a href="#"><?php echo $_SESSION["username"] ?></a></li>
			</ul>
		<?php else: ?>
			<ul>
				<li><a href="/login.php">Login</a></li>
				<li><a href="/register.php">Register</a></li>
			</ul>
		<?php endif; ?>
	</div>
</nav>
