<?php
/**
 * Pied de page HTML commun à tous les emails du site.
 * Variable attendue : $emailFooterNote
 */
?>
		</div>
		<div class="footer">
			<p><?= htmlspecialchars($emailFooterNote) ?></p>
			<p>Date : <?= date('d/m/Y à H:i') ?></p>
		</div>
	</div>
</body>
</html>