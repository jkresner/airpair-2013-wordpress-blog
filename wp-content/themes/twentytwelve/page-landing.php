<?php
/*
Template Name: Landing
*/
?>

<?php get_header('landing'); ?>

<nav class="cf">
  <a class="logo" href="/"></a>
  <ul class="social">
    <li><a href="http://twitter.com/airpair" target="_blank" class="twitter"></a></li>
    <li><a href="http://facebook.com/airpair" target="_blank" class="facebook"></a></li>
  </ul>
  <ul class="navigation">
    <li><a href="/pair-programming"><span>{</span> pair programming <span>}</span></a></li>
    <li class="active"><a href="/code-review"><span>{</span> code review <span>}</span></a></li>
    <li><a href="/problem-solving"><span>{</span> problem solving <span>}</span></a></li>
    <li><a href="/code-mentoring"><span>{</span> code mentoring <span>}</span></a></li>
  </ul>
</nav>

<?php the_content(); ?>


<div class='fold-border'></div>
<div id="cost" class="outer-container cf">
  <div class="middle-container">
    <div class="inner-container marketing">
      <div class="tagline"><h1>How much does it cost?</h1></div>

      <p>Airpair is a marketplace. Experts set their own rate and we match you corresponding to your budget.</p>

      <table>
        <tr>
          <th>$60<i>/hr</i></th>
          <th>$90<i>/hr</i></th>
          <th>$130<i>/hr</i></th>
          <th>$200<i>/hr</i></th>
          <th>$300<i>/hr</i></th>
        </tr>
        <tr>
          <td><div>Access 15%</div> of our experts <em>Solve any beginner challenge</em></td>
          <td><div>Access 40%</div> of our experts <em>Write software like an expert</em></td>
          <td><div>Access 60%</div> of our experts <em>Solve deep technical problems</em></td>
          <td><div>Access 90%</div> of our experts <em>Get solutions from world class talent</em></td>
          <td><div>Access 100%</div> of our experts <em>Leverage industry thought leaders</em></td>
        </tr>
      </table>

      <div class="inner-container">
        <div style="clear:both"></div><br />
        <a href="/find-an-expert" class="button" style="text-align:center;width:640px"> Get started</a>
      </div>
    </div>
  </div>
</div>

<footer>
    &copy; <a href="http://airpair.com/">airpair, inc. 2013 | </a>
</footer>

<script src="/javascripts/providers.js" defer onload="require('scripts/providers/all');"></script>

</body>
</html>