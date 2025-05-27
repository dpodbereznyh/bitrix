<?php if (!empty($arResult)): ?>
    <div class="random-products">
        <?php foreach ($arResult as $product): ?>
            <div class="product">
                <h2>
                    <?= $product['NAME'] ?>
                </h2>
                <a href="<?= $product['DETAIL_PAGE_URL'] ?>"><?= $product['NAME'] ?></a>
                <p>Цена: <?= $product['PRICE'] ?> руб.</p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Товары не найдены.</p>
<?php endif; ?>