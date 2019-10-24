// JavaScript Document
var titleField =  document.getElementById('imageTitle');
alert('chicken '+titleField);

titleField.onblur = titleToSlug;

function titleToSlug()
  {
  var titleSlug = "";

  var titleSlug = titleField.value;
  alert('hi');
  var titleSlug = titleSlug.trim();
  var titleSlug = titleSlug.replace(/\b(a|an|and|as|at|but|by|in|nor|of|or|so|the|to|up|via)\b/g,'');
  var titleSlug = titleSlug.replace(/\s+/g, '-')                                                                           // Change spaces to dashes
  .replace(/\-\-+/g, '-')                                                                         // Replace multiple dashes with a single dash
  .toLowerCase()
  .substring(0,64);

return alert('ninja'+titleSlug);
  }
