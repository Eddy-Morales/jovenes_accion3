    </main>

    <!-- Simple Footer -->
    <footer class="relative z-10 glass border-t border-white/50 py-6 mt-auto">
        <div class="container mx-auto text-center text-sm text-gray-500">
            <p>&copy; <?php echo date('Y'); ?> Ant Project. Todos los derechos reservados.</p>
        </div>
    </footer>

    <?php
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        $type = $msg['type'];
        $title = $msg['title'] ?? ucfirst($type);
        $text = $msg['text'];
        
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '" . addslashes($title) . "',
                    text: '" . addslashes($text) . "',
                    icon: '$type',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#7c3aed'
                });
            });
        </script>";
        unset($_SESSION['flash_message']);
    }
    ?>

</body>
</html>
