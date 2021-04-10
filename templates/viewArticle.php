<?php include "templates/include/header.php" ?>
	  
    <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
    <?php if(!empty($results['authors'])) { ?>
          
        <h2><?php echo "Авторы" ?></h2>
       
        <?php foreach($results['authors'] as $author) { ?>
                <h3>
                    <span class="author">
                        <?php echo $author->login ?>
                    </span>
                </h3>
            <?php } ?>
       <?php } ?>
    
    <div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
    <div style="width: 75%;"><?php echo $results['article']->content?></div>
    <p class="pubDate">Published on <?php  echo date('j F Y', $results['article']->publicationDate)?>
    
    <?php if ( $results['category'] ) { ?>
        in 
        <a href="./?action=archive&amp;categoryId=<?php echo $results['category']->id?>">
            <?php echo htmlspecialchars($results['category']->name) ?>
        </a>
    <?php } ?>
        
    </p>

    <p><a href="./">Вернуться на главную страницу</a></p>
	  
<?php include "templates/include/footer.php" ?>    
                