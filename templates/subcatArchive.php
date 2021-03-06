<?php include "templates/include/header.php" ?>

<h1><?php echo htmlspecialchars ($results['pageHeading'])?> </h1>
<?php if($results["subcategories"]){ ?>
<h3 class="subcategoriesDescription"> <?php echo htmlspecialchars
($results['subcategoryId']->description) ?> </h3> 
<?php }?>

<ul id="tablesubcat" class="subcatarchive">
    
<?php foreach ($results["articles"] as $article) { ?>
    
    <li>
        <h2>
            <span class="pubDate">
                <?php echo date("j F Y", $article->publicationDate) ?>
            </span> 
            <a href=".?action=viewSubcategory&amp;articleId=<?php echo $article->id ?>">
            <?php echo htmlspecialchars($article->title) ?>
            </a>
            
            <?php if(!$results['subcategoryId'] && $article->subcategoryId) { ?>
                <span class="subcategory">
                    in
                    <a href=".?action=subcatArchive&amp;subcategoryId=<?php echo
                    $article->subcategoryId ?>">
                    <?php echo htmlspecialchars($results["subcategories"][$article->subcategoryId]->titleSubcat) ?>
                    </a>
                </span>
                
           <?php } ?>
            
            
        </h2> 
        
        
        
        
        
        
        
    </li>
<?php } ?>
</ul>