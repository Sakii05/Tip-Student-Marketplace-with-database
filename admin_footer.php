  </div><!-- end content-area -->
</div><!-- end admin-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('show');
  }
  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('adminSidebar');
    if (window.innerWidth < 768 && !sidebar.contains(e.target) && sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
    }
  });
</script>
</body>
</html>
