<div>
    <div>
        <h2>Partenaires</h2>
        <ul>
            <li>

                <h3>Coca-Cola</h3>
                <br>
                <?= $this->Html->image('cocacola.png', ['alt' => 'CocaCola']); ?>
                <?= $this->Html->link( 'Cocacola link!', 'https://www.cocacola.com/' ); ?>
            </li>
            <li>

                <h3>Pepsi</h3>
                <br>
                <?= $this->Html->image('pepsi.png', ['alt' => 'Pepsi']); ?>
                <?= $this->Html->link( 'Pepsi link!', 'https://www.pepsi.com/' ); ?>
            </li>
            <h3>Bpost</h3>
            <br>
            <?= $this->Html->image('bpost.png', ['alt' => 'Bpost']); ?>
            <?= $this->Html->link( 'Bpost link!', 'https://track.bpost.cloud/btr/web/#/home?lang=fr' ); ?>
            </li>
            <li>

                <h3>Github</h3>
                <br>
                <?= $this->Html->image('github.png', ['alt' => 'GitHub']); ?>
                <?= $this->Html->link( 'Github link!', 'https://www.github.com/' ); ?>
            </li>
            <h3>Fedex</h3>
            <?= $this->Html->image('fedex.jpg', ['alt' => 'FedEx']); ?>
            <?= $this->Html->link( 'Fedex link!', 'https://www.fedex.com/' ); ?>
            </li>
        </ul>
    </div>
</div>
