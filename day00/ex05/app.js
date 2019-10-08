const state = {
  items: {
    dog: false,
    slack: function () {
      changeRoom('serega.jpg');
    },
    golden: function () {
      loseItem('slack', '');
      changeRoom('win.jpg');
    },
  },
  selected: null,
  room: 'main_01.jpg',
  score: 0,
};

const actions = {
  'main_01.jpg': {
    advance: 'oxygen.jpg',
    entry: 'Привет! Ты попал на обязательную педагогическую встречу!',
    take: {
      text: 'Из этой локации нечего взять :Kappa:.',
      items: [],
    },
    look: 'Ты видишь вокруг много воодушевленных пепег.',
    speak: {
      text: 'Ты пытаешься заговорить со студентом, но он лишь шепчет в ответ: "СВЯТОЙ ТИЖ ЗАХВАТИТ ТЕБЯ". Ты решаешь больше не говорить с ним.',
      action: function () {},
    }
  },
  'oxygen.jpg': {
    advance: 'oxygen.jpg',
    entry: 'Прошло какое-то время и ты (опять?) попал в Oxygen.',
    take: {
      text: 'Больше здесь ничего нет.',
      items: [
        {
          id: 'slack',
          text: 'Воспользовавшись клавиатурой, почтой и умом сидевшего рядом пира ты попал в Слак!'
        }
      ],
    },
    look: 'Вокруг какие-то пепеги. Они слабы. Это видно по их глазам.',
    speak: {
      text: 'Ты задаешь вопрос: "Как искать по LDAP?". Ответ тебе служит тишина.',
      action: function () {},
    }
  },
  'serega.jpg': {
    advance: 'oasis.jpg',
    entry: 'В Слаке ты увидел, что Серега вызывает тебя на дуэль.',
    take: {
      text: 'You can\'t pickup Serega :KappaPride:',
      items: [],
    },
    look: 'Четкие черти внимательно следят за геймплеем.',
    speak: {
      text: 'Ты говоришь с Серегой про свою либу. Interesting conversation isn\'t it?',
      action: function () {},
    }
  },
  'oasis.jpg': {
    advance: 'oasis.jpg',
    entry: 'Серега выиграл тебя, но все равно разрешил посетить лучший кластер в Школе - Чилазис!',
    take: {
      text: 'У тебя уже есть золотой билет, что еще тебе нужно?',
      items: [
        {
          id: 'golden',
          text: 'Ты увидел лежавший на полу золотой билет. И забрал его.',
        }
      ],
    },
    look: 'Вокруг достаточно темно, но слишком громко. Куда же смотрит Дружина?',
    speak: {
      text: '',
      action: function () {
        loseItem('slack', '"Deus Vult!", - перебивает тебя какой-то студент, еще до того как ты успеваешь что-то произнести. Потом твой телефон хватает дружинник и вот уже в #bocal красуется собака-шанель...');
        state.score -= 5;
        actions['oasis.jpg'].advance = 'gameover.png';
      },
    }
  },
  'gameover.png': {
    advance: 'gameover.png',
    entry: 'Game Over :PepeRain:',
    take: {
      text: 'Game Over :PepeRain:',
      items: [],
    },
    look: 'Game Over :PepeRain:',
    speak: {
      text: 'Thanks Obama :OMEGALUL:',
      action: function () {},
    }
  },
  'win.jpg': {
    advance: 'win.jpg',
    entry: 'Winner Winner Oasis TOP',
    take: {
      text: 'Winner Winner Oasis TOP',
      items: [],
    },
    look: 'Winner Winner Oasis TOP',
    speak: {
      text: '',
      action: function () {
        $('#action').text(`Твой счет: ${state.score}! :kekBomb:`);
      },
    }
  }
};

const changeRoom = (newroom) => {
  state.room = newroom;

  $('#action').text(actions[state.room].entry);
  $('#main-image').attr('src', `resources/${newroom}`);
};

const roll = (max) => Math.floor((Math.random() * max));

const loseItem = (item, text) => {
  state.selected = null;
  $('.item-icon').removeClass('selected');
  $(`#item-${item}`).addClass('hidden');
  $('#action').text(text);
}

$('#action-advance').click(e => {
  e.preventDefault();

  changeRoom(actions[state.room].advance);
});

$('#action-take').click(e => {
  e.preventDefault();

  $('#action').text(actions[state.room].take.text);

  if (actions[state.room].take.items.length > 0) {
    const curRoll = roll(actions[state.room].take.items.length);
    const item = actions[state.room].take.items[curRoll];
    actions[state.room].take.items.splice(curRoll, 1);
    $('#action').text(item.text);
    $(`#item-${item.id}`).removeClass('hidden');
    state.score += 5;
  }
  $('#action').text();
});

$('#action-look').click(e => {
  e.preventDefault();

  $('#action').text(actions[state.room].look);
});

$('#action-use').click(e => {
  e.preventDefault();

  if (state.selected == null)
  {
    $('#action').text('Было бы неплохо воспользоваться каким-нибудь предметом, но для этого нужно его выбрать. За использование невыбранного предмеиа дают ТИЖ.');
    return;
  }

  state.items[state.selected]();
});

$('#action-speak').click(e => {
  e.preventDefault();

  $('#action').text(actions[state.room].speak.text);
  actions[state.room].speak.action();
});

$('.item-icon').click(function (e) {
  e.preventDefault();

  $('.item-icon').removeClass('selected');
  $(this).addClass('selected');
  state.selected = $(this).data('id');
});

$('#disconnect').click(function (e) {
  e.preventDefault();
  alert('В большинстве современных браузеров вам придется закрыть вкладку вручную.');
  window.close();
});

changeRoom('main_01.jpg');
