<?php include "templates/include/header.php" ?>
<h2><?php echo htmlspecialchars ($results['pageTitle'])?> </h2>
<h1 class="authorName"> <?php echo htmlspecialchars
($nameAuthor) ?> </h1> 

<ul id="tablebooks" class="books">
    
<?php foreach ($results["books"] as $books) { ?>
       <?php $i = 0; $j = 1; $k = 2 ?>
        <li>
             <h2>
                 <span class="pubDate">
                     <?php echo date('j F Y', $books[$i]->publicationDate)?>
                 </span>
                 <span class="pubDate">
                     Категория
                     <?php echo ($books[$j]->name)?>
                 </span>
                 <span class="pubDate">
                     Подкатегория
                     <?php echo ($books[$k]->titleSubcat)?>
                 </span>
                 <div><a href=".?action=viewArticle&amp;articleId=<?php echo $books[$i]->id?>">
                     <?php echo htmlspecialchars( $books[$i]->title )?>
                 </a>
                 </div>
             </h2>
           <p class="summary"><?php echo htmlspecialchars( $books[$i]->summary )?></p>
         </li>

    <?php } ?>

    </ul>

    <!--<p><?php echo $results['totalRows']?> article<?php echo ( $i != 1 ) ? 's' : '' ?> in total.</p>-->

    <p><a href="./">Return to Homepage</a></p>
	  
<?php include "templates/include/footer.php" ?>