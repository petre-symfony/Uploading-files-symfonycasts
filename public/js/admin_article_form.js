Dropzone.autoDiscover = false;

$(document).ready(function() {
  initializeDropzone();
  var $locationSelect = $('.js-article-form-location');
  var $specificLocationTarget = $('.js-specific-location-target');
  var referenceList = new ReferenceList($('.js-reference-list'));

  $locationSelect.on('change', function(e) {
    $.ajax({
      url: $locationSelect.data('specific-location-url'),
      data: {
        location: $locationSelect.val()
      },
      success: function (html) {
        if (!html) {
          $specificLocationTarget.find('select').remove();
          $specificLocationTarget.addClass('d-none');

          return;
        }

        // Replace the current field and show
        $specificLocationTarget
          .html(html)
          .removeClass('d-none')
      }
    });
  });
});

// todo - use Webpack Encore so ES6 syntax is transpiled to ES5
class ReferenceList {
  constructor($element) {
    this.$element = $element;
    this.references = [];
    this.render();
    $.ajax({
      url: this.$element.data('url')
    }).then(data => {
      this.references = data;
      this.render();
    })
  }
  render() {
    const itemsHtml = this.references.map(reference => {
      return `
        <li class="list-group-item d-flex justify-content-between align-items-center">
          ${reference.originalFilename}<span>
            <a href="/admin/article/references/${reference.id}/download"><span class="fa fa-download"></span></a>
          </span>
        </li>
      `
    });
    this.$element.html(itemsHtml.join(''));
  }
}

function initializeDropzone() {
  var formElement = document.querySelector('.js-reference-dropzone');
  if(!formElement){
    return;
  }

  var dropzone = new Dropzone(formElement, {
    'paramName': 'reference',
    init: function() {
      this.on('error', function(file, data) {
        if (data.detail) {
          this.emit('error', file, data.detail);
        }
      });
    }
  })
}
