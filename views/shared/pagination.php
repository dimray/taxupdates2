<?php if (!empty($pagination)): ?>

    <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="pagination">
            <ul>
                <?php if ($pagination['has_prev_page']): ?>
                    <li><a href="?page=<?= $pagination['prev_page'] ?>">Previous</a></li>
                <?php endif; ?>

                <?php if ($pagination['has_next_page']): ?>
                    <li><a href="?page=<?= $pagination['next_page'] ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>

<?php endif; ?>