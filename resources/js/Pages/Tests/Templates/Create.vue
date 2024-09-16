<template>
    <h1 class="main">Quiz app in Vue</h1>
    
    <div id="quiz-app">
        <div class="status">
            {{ questions }}
        </div>

  <!-- <div class="user-role">
      <div class="question current">
        <transition name="slide-fade" mode="out-in">
          <div :key="questions.value[currentQuestion].question" :class="{'deactivate': answered == questions.value.length}">
           <h2>{{ questions.value[currentQuestion].question }}</h2>
           <div class="answers">
             <span v-for="(answer, index) in questions.value[currentQuestion].answers" :key="index" v-bind:data-index="index" @click="selectAnswer">{{ answer }}</span>
           </div>
          </div>
        </transition>
         <div class="next-question">
           <button class="next-btn" disabled>{{ currentQuestion < (questions.value.length -1) ? 'Next' : 'Result!' }}</button>      
         </div>
      </div>
    
    <div class="result">
      <div class="success"></div>
      <h2>You have successfully finished the quiz, and your score is:</h2>
      <h1 :class="[(Number(((correctAnswers / questions.value.length) *100)).toFixed(2) >= 50) ? 'green' : 'red']">{{ Number(((correctAnswers / questions.length) *100)).toFixed(2) }}%</h1>
      <small><b>{{ correctAnswers }}</b>Correct, <b>{{ wrongAnswers }}</b>Wrong</small>
      <button class="close">close</button>
      <button class="show-wrong-ones"
              v-show="wrongAnswers > 0"
              @click="showWrongQuestion = true">Wrong answers</button>
    </div>
  </div> -->
    
   <!-- <div class="wrong-questions">
     <h2 v-if="wrongQuestions.length > 1">Your wrong Questions</h2>
     <h2 v-else-if="wrongQuestions.length == 1">Your wrong Question</h2>
     <div class="wrong-one" v-for="question in wrongQuestions">
       <h3>{{ question.question }}</h3>
       <div class="answers-container">
         <span class="selected">{{ question.answers[question.selected] }}</span>
         <span class="correct">{{ question.answers[question.correct_answer] }}</span>
       </div>
     </div>
     
     <button id="return-to-result">Show my result</button>
   </div> -->
</div>

</template>
  
<script script>
    import { reactive } from 'vue';

    const questions = reactive([
            {
            question: 'Which company was established on April 1st, 1976 by Steve Jobs, Steve Wozniak and Ronald Wayne?',
            answers: [
                'Apple',
                'Microsoft',
                'Atari',
                'Commodore'
            ],
            correct_answer: 0,
            selected: null,
            sense: 0
            },
            {

            question: 'In any programming language, what is the most common way to iterate through an array?',
            answers: [
                'If Statements',
                'Do-while loops',
                'For loops',
                'While loops'
            ],
            correct_answer: 2,
            selected: null,
            sense: 0
            },
            {
            question: 'According to the International System of Units, how many bytes are in a kilobyte of RAM?',
            answers: [
                '512',
                '1000',
                '1024',
                '500'
            ],
            correct_answer: 1,
            selected: null,
            sense: 0
            },
            {
            question: 'HTML is what type of language?',
            answers: [
                'Markup Language',
                'Macro Language',
                'Programming Language',
                'Scripting Language'
            ],
            correct_answer: 0,
            selected: null,
            sense: 0
            },
            {
            question: 'What amount of bits commonly equals one byte?',
            answers: [
                '1',
                '2',
                '64',
                '8'
            ],
            correct_answer: 3,
            selected: null,
            sense: 0
            },
            {
            question: "If you were to code software in this language you'd only be able to type Ones and Zeros.",
            answers: [
                'JavaScript',
                'Binary',
                'C++',
                'Python'
            ],
            correct_answer: 1,
            selected: null,
            sense: 0
            },
            {
            question: 'What is the most preferred image format used for logos?',
            answers: [
                '.png',
                '.jpeg',
                '.svg',
                '.gif'
            ],
            correct_answer: 2,
            selected: null,
            sense: 0
            },
            {
            question: 'In web development, what does CSS stand for?',
            answers: [
                'Counter Strike: Source',
                'Cascading Style Sheet',
                'Corrective Style Sheet',
                'Computer Style Sheet'
            ],
            correct_answer: 1,
            selected: null,
            sense: 0
            },
            {
            question: 'In "Hexadecimal", what color would be displayed from the color code? #00FF00?',
            answers: [
                'Red',
                'Green',
                'Blue',
                'Yellow'
            ],
            correct_answer: 1,
            selected: null,
            sense: 0
            },
            {
            question: 'The C programming language was created by this American computer scientist. ',
            answers: [
                'Dennis Ritchie',
                'Tim Berners Lee',
                'al-Khwārizmī',
                'Willis Ware'
            ],
            correct_answer: 0,
            selected: null,
            sense: 0
            }
        ]);

    const showWrongQuestion = false;
    const wrongQuestions = [];
    const temp = [];
    const currentQuestion = 0;
    const answered = 0;
    const wrongAnswers = 0;
    const correctAnswers = 0;

    function selectAnswer(e) {
      var choise = e.currentTarget,
          answers = document.querySelectorAll('.answers span'),
          nextBtn = document.querySelector('.next-btn');
      
      answers.forEach(answer => {
        answer.classList.contains('selected') ? answer.classList.remove('selected') : '';
      });
      
      choise.classList.add('selected');
      
      this.questions[this.currentQuestion].selected = choise.dataset.index; // add the selected index to the obj in the 'selected' property
      
      nextBtn.removeAttribute('disabled');
      
    }

    function calculateResult(questions) {
      var correct;
      
      for(var i=0; i< questions.length; i++) {
        this.questions[i].selected == questions[i].correct ? correct++ : '';
      }
      
      return (correct / questions.length) * 100;
    }

//   mounted() {
    
//     var nextBtn = this.$el.querySelector('.next-btn'),
//         wrongAnswersBtn = this.$el.querySelector('.show-wrong-ones'),
//         answers = this.$el.querySelectorAll('.answers span'),
//         questionsLength = this.questions.length,
//         result = this.$el.querySelector('.result'),
//         question = this.$el.querySelector('.question'),
//         closeResult = this.$el.querySelector('.result button.close'),
//         wrongQuestions = this.$el.querySelector('.wrong-questions'),
//         showMyResults = this.$el.querySelector('#return-to-result');
          
//     nextBtn.addEventListener('click', () => {
      
//       this.answered < this.questions.length ? this.answered++ : '';
      
//       if(!nextBtn.hasAttribute('disabled') && this.currentQuestion < (questionsLength -1)) {    
//         this.currentQuestion++;
        
//         answers.forEach(answer => {
//           answer.classList.contains('selected') ? answer.classList.remove('selected') : '';
//         });
        
//         nextBtn.setAttribute('disabled', '');
        
//       } else if(this.currentQuestion >= (questionsLength -1)) {
        
//         this.questions.forEach( (question) => {
//           if(question.selected == question.correct_answer && question.sense ==0) {
            
//             this.correctAnswers++;
//             question.sense = 1;
            
//           } else if(question.selected != question.correct_answer && question.sense ==0) {
            
//             this.wrongAnswers++;
//             question.sense = 1;
//             let temp = {};
//             temp.answers = question.answers;
//             temp.question = question.question;
//             temp.correct_answer = question.correct_answer;
//             temp.selected = question.selected;
            
//             this.wrongQuestions.push(temp);
//           }
//         });
        
//         result.classList.add('active');
//         question.classList.add('blur');
//       }
//     });
    
//     closeResult.addEventListener('click', () => {
//       result.classList.remove('active');
//       question.classList.remove('blur');
//     });
    
//     wrongAnswersBtn.addEventListener('click', () => {      
//       result.classList.remove('active');
//       wrongQuestions.classList.add('active');
//     });
    
//     showMyResults.addEventListener('click', () => {
//       result.classList.add('active');
//       wrongQuestions.classList.remove('active');
//     })
//   }
// });
</script>
 
<style scoped>
@import url('https://fonts.googleapis.com/css?family=Bad+Script');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  user-select: none;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
background: #cb2d3e;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #ef473a, #cb2d3e);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #ef473a, #cb2d3e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  height: 100vh;
  max-height: 100vh;
  overflow: hidden;
}

h1.main{
  font-weight: 100;
  color: #fff;
  text-align: center;
  margin: 0;
}

.user-role {
  transition: opacity .2s ease;
}

.user-role.mute {
  opacity: .5;
}

.question{
  max-width: 650px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateY(-50%) translateX(-250%);
  background-color: rgba(255, 255, 255, .25);
  padding: 10px;
  border-radius: 4px;
  overflow: hidden;
  transition: transform .4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.question.current {
  transform: translateY(-50%) translateX(-50%);
}

.wrong.current {
  transform: translateY(-50%) translateX(-50%);
}

.question.blur {
  filter: blur(2px);
  pointer-events: none;
}

.deactivate {
  pointer-events: none;
}

.question h2{
  min-width: calc(100% - 20px);  
  max-width: calc(100% - 20px);
  background-color: transparent;
  color: #fff;
  font-weight: 100;
  padding: 0;
  border-radius: 3px;
}

.question .answers {
  display: flex;
  flex-wrap: wrap;
}

.question .answers span{
  display: inline-block;
  width: calc(50% - 5px);
  max-width: calc(50% - 5px);
  background-color: rgba(255, 255, 255, .5);
  color: #cb2d3e;
  font-size: 1.05em;
  margin: 5px 5px 0 0;
  padding: 5px;
  border-radius: 3px;
  cursor: pointer;
}

.question .answers span.selected {
  animation: select-answer .2s ease;
  background-color: #fff;
}

@keyframes select-answer {
  0% {
    transform: scale(0.95);
  }
  100% {
    transform: scale(1);
  }
}

.question .next-question {
  margin-top: 15px;
  padding-right: 5px;
  text-align: right;
}

.question .next-btn {
  background-color: #fff;
  border: none;
  padding: 3px 20px;
  border-radius: 3px;
  font-size: 1.5em;
  color: #cb2d3e;
  cursor: pointer;
  transition: all .5s ease;
}

.question .next-btn:focus {
  outline: none;
}

.question .next-btn[disabled] {
  opacity: .6;
  cursor: no-drop
}

.slide-fade-enter-active {
  transition: all .3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.slide-fade-leave-active {
  transition: all .3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.slide-fade-enter {
  transform: translateY(-40%);
  opacity: 0;
}

.slide-fade-leave-to /* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateY(40%);
  opacity: 0;
}

.status {
  font-size: 0.8em;
  margin: 3px auto 0;
  text-align: center;
  padding: 5px;
  border-radius: 2px;
  color: rgba(255, 255, 255, .5);
  transition: color .3s ease;
}

.status:hover {
  color: #fff;
}



.result{
  max-width: 560px;
  overflow: hidden;
  text-align: center;
  position: absolute;
  top: -50%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%);
  padding: 15px;
  border-radius: 5px;
  background-color: #fff;
  transition: all .3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.result.active {
  top: 50%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%);
}

.result svg {
  position: absolute;
  opacity: 0.15
}

.result .success {
  position: absolute;
  top: -10px;
  left: 10px;
  width: calc(100% - 20px);
  height: 100%;
  opacity: 0.1;
  background-image: url("data:image/svg+xml,%0A%3Csvg height='480pt' viewBox='0 -52 480.0106 480' width='480pt' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3ClinearGradient id='a'%3E%3Cstop offset='0' stop-color='%23e93e3a'/%3E%3Cstop offset='.358' stop-color='%23ed683c'/%3E%3Cstop offset='.7' stop-color='%23f3903f'/%3E%3Cstop offset='1' stop-color='%23fdc70c'/%3E%3C/linearGradient%3E%3ClinearGradient id='b' gradientUnits='userSpaceOnUse' x1='240.0013' x2='240.0013' xlink:href='%23a' y1='389.0066' y2='-34.6654'/%3E%3ClinearGradient id='c' gradientUnits='userSpaceOnUse' x1='379.5933' x2='379.5933' xlink:href='%23a' y1='389.0066' y2='-34.6654'/%3E%3ClinearGradient id='d' gradientUnits='userSpaceOnUse' x1='100.4093' x2='100.4093' xlink:href='%23a' y1='389.0066' y2='-34.6654'/%3E%3Cpath d='m4.167969 15.039062c9.960937 5.148438 20.222656 9.691407 30.730469 13.597657-.113282.933593-.058594 1.878906.160156 2.792969l36 138.503906c.761718 2.933594 3.113281 5.1875 6.078125 5.816406.546875.113281 1.105469.167969 1.664062.167969 2.4375 0 4.738281-1.109375 6.257813-3.015625l86.300781-108.273438 61.480469 122.960938c1.355468 2.714844 4.128906 4.433594 7.160156 4.433594 3.035156 0 5.808594-1.71875 7.160156-4.433594l61.480469-122.960938 86.3125 108.265625c1.519531 1.90625 3.820313 3.015625 6.257813 3.015625.558593 0 1.113281-.054687 1.664062-.167968 2.964844-.628907 5.316406-2.882813 6.078125-5.816407l36-138.503906c.21875-.914063.269531-1.859375.160156-2.792969 10.507813-3.90625 20.769531-8.449218 30.726563-13.597656 3.878906-2.117188 5.308594-6.976562 3.195312-10.855469-2.117187-3.878906-6.980468-5.308593-10.859375-3.191406-.863281.476563-88.175781 47.03125-228.175781 47.03125s-227.3125-46.554687-228.167969-47.023437c-3.878906-2.117188-8.738281-.6875-10.855469 3.191406-2.117187 3.878906-.6875 8.738281 3.191407 10.855468zm78.335937 135.375-28.703125-110.496093 99.871094 21.234375zm157.496094 15.710938-51.054688-102.109375h102.113282zm157.496094-15.710938-71.199219-89.261718 99.871094-21.234375zm0 0' fill='url(%23b)'/%3E%3Cpath d='m208 75.816406c-1.453125 2.355469-1.585938 5.296875-.351562 7.773438l25.160156 50.335937c1.355468 2.714844 4.125 4.433594 7.160156 4.433594s5.808594-1.71875 7.160156-4.433594l25.160156-50.335937c1.238282-2.472656 1.105469-5.410156-.339843-7.765625-1.449219-2.355469-4.015625-3.796875-6.78125-3.808594h-50.335938c-2.78125-.007813-5.371093 1.429687-6.832031 3.800781zm44.21875 12.199219-12.21875 24.445313-12.214844-24.445313zm0 0' fill='url(%23b)'/%3E%3Cpath d='m348.722656 76.398438 35.078125 44c1.519531 1.90625 3.820313 3.015624 6.257813 3.015624.558594 0 1.117187-.058593 1.664062-.175781 2.964844-.628906 5.316406-2.882812 6.078125-5.816406l14.144531-54.472656c.695313-2.683594-.046874-5.535157-1.964843-7.535157-1.914063-2.003906-4.730469-2.871093-7.441407-2.296874l-49.226562 10.457031c-2.707031.578125-4.921875 2.515625-5.859375 5.121093-.933594 2.605469-.453125 5.511719 1.269531 7.679688zm44.480469-4.929688-6.867187 26.449219-17.039063-21.375zm0 0' fill='url(%23c)'/%3E%3Cpath d='m68.058594 62.957031 14.144531 54.472657c.757813 2.933593 3.113281 5.1875 6.078125 5.816406.546875.117187 1.105469.175781 1.664062.175781 2.4375 0 4.738282-1.109375 6.257813-3.015625l35.078125-44c1.722656-2.167969 2.203125-5.070312 1.265625-7.675781-.933594-2.609375-3.148437-4.546875-5.859375-5.125l-49.222656-10.453125c-2.710938-.578125-5.527344.289062-7.445313 2.292968-1.914062 2.003907-2.65625 4.855469-1.960937 7.539063zm42.648437 13.601563-17.042969 21.375-6.863281-26.445313zm0 0' fill='url(%23d)'/%3E%3Cpath d='m468.167969 184.992188c-.855469.46875-88.167969 47.023437-228.167969 47.023437s-227.3125-46.554687-228.167969-47.023437c-3.878906-2.117188-8.738281-.6875-10.855469 3.191406-2.117187 3.878906-.6875 8.738281 3.191407 10.855468 9.960937 5.148438 20.222656 9.691407 30.730469 13.597657-.113282.933593-.058594 1.878906.160156 2.792969l36 138.503906c.761718 2.933594 3.113281 5.1875 6.078125 5.816406.546875.113281 1.105469.167969 1.664062.167969 2.4375 0 4.738281-1.109375 6.257813-3.015625l86.300781-108.273438 61.480469 122.960938c1.355468 2.714844 4.128906 4.433594 7.160156 4.433594 3.035156 0 5.808594-1.71875 7.160156-4.433594l61.480469-122.960938 86.3125 108.265625c1.519531 1.90625 3.820313 3.015625 6.257813 3.015625.558593 0 1.113281-.054687 1.664062-.167968 2.964844-.628907 5.316406-2.882813 6.078125-5.816407l36-138.503906c.21875-.914063.269531-1.859375.160156-2.792969 10.507813-3.90625 20.769531-8.449218 30.726563-13.597656 3.878906-2.117188 5.308594-6.976562 3.195312-10.855469-2.117187-3.878906-6.980468-5.308593-10.859375-3.191406zm-385.664063 149.421874-28.703125-110.496093 99.871094 21.234375zm157.496094 15.710938-51.054688-102.109375h102.113282zm157.496094-15.710938-71.199219-89.261718 99.871094-21.234375zm0 0' fill='url(%23b)'/%3E%3Cpath d='m208 259.816406c-1.453125 2.355469-1.585938 5.296875-.351562 7.773438l25.160156 50.335937c1.355468 2.714844 4.125 4.433594 7.160156 4.433594s5.808594-1.71875 7.160156-4.433594l25.160156-50.335937c1.238282-2.472656 1.105469-5.410156-.339843-7.765625-1.449219-2.355469-4.015625-3.796875-6.78125-3.808594h-50.335938c-2.78125-.007813-5.371093 1.429687-6.832031 3.800781zm44.21875 12.199219-12.21875 24.445313-12.214844-24.445313zm0 0' fill='url(%23b)'/%3E%3Cpath d='m348.722656 260.398438 35.078125 44c1.519531 1.90625 3.820313 3.015624 6.257813 3.015624.558594 0 1.117187-.058593 1.664062-.175781 2.964844-.628906 5.316406-2.882812 6.078125-5.816406l14.144531-54.472656c.695313-2.683594-.046874-5.535157-1.964843-7.535157-1.914063-2.003906-4.730469-2.871093-7.441407-2.296874l-49.226562 10.457031c-2.707031.578125-4.921875 2.515625-5.859375 5.121093-.933594 2.605469-.453125 5.511719 1.269531 7.679688zm44.480469-4.929688-6.867187 26.449219-17.039063-21.375zm0 0' fill='url(%23c)'/%3E%3Cpath d='m68.058594 246.957031 14.144531 54.472657c.757813 2.933593 3.113281 5.1875 6.078125 5.816406.546875.117187 1.105469.175781 1.664062.175781 2.4375 0 4.738282-1.109375 6.257813-3.015625l35.078125-44c1.722656-2.167969 2.203125-5.070312 1.265625-7.675781-.933594-2.609375-3.148437-4.546875-5.859375-5.125l-49.222656-10.453125c-2.710938-.578125-5.527344.289062-7.445313 2.292968-1.914062 2.003907-2.65625 4.855469-1.960937 7.539063zm42.648437 13.601563-17.042969 21.375-6.863281-26.445313zm0 0' fill='url(%23d)'/%3E%3C/svg%3E");
  background-size: 120px;
  background-repeat: repeat-x;
}

.result h2 {
  font-family: 'Bad Script', cursive;
  font-weight: 400;
  text-align: center;
}

.result h1 {
  font-size: 2.3em;
  font-weight: 400;
  margin-bottom: -10px;
}

.result small {
  color: #887;
  font-size: 0.8em;
}

.result button {
  position: absolute;
  bottom: 7px;
  background-color: transparent;
  border: none;
  outline: none;
  border-radius: 5px;
  color: #d21d0f;
  cursor: pointer;
  font-size: 1.05em;
  font-family: 'Bad Script', cursive;
  transition: box-shadow .2s ease;
}

.result button.close {
  right: 10px;
  padding: 0 15px;
  font-size: 1.3em;
}

.result button.show-wrong-ones {
  left: 10px;
  padding: 5px;
}

.result button:hover {
  box-shadow: 4px 6px 15px 1px #ddd;
}


.green {
  color: #0dcc0d;
}

.red {
  color: #d21d0f;
}

.wrong-questions {
  width: 80vw;    
  max-height: 100vh;
  text-align: center;
  overflow-y: scroll;
  padding: 5px;
  position: absolute;
  top: 150%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%);
  border-radius: 5px;
  background-color: #fff;
  transition: all .4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.wrong-questions.active {
  top: 50%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%);
}

.wrong-questions::-webkit-scrollbar-track {
	border-radius: 10px;
}

.wrong-questions::-webkit-scrollbar {
	width: 4px;
}

.wrong-questions::-webkit-scrollbar-thumb {
	border-radius: 10px;
	background-color: #e6e6e6;
}

.wrong-questions h2 {
  font-weight: 100;
  margin: 10px 0;
  text-align: center;
  color: #d21d0f;
}

.wrong-questions .wrong-one {
  width: 90%;
  text-align: left;
  margin: 0 auto;
  padding: 3px 7px;
  border-bottom: 1px solid #eee;
}

.wrong-questions .wrong-one h3 {
  font-weight: 100;
}

.wrong-questions .answers-container {
  padding-right: 40px;
}

.wrong-questions .answers-container span {
  display: block;
  padding: 0 7px;
  position: relative;
}

.wrong-questions .answers-container span:after {
  position: absolute;
  top: 0px;
  right: -40px;
  font-weight: 100;
  font-size: .9em;
}

.wrong-questions .answers-container span.selected {
  color: #d21d0f;
}

.wrong-questions .answers-container span.selected:after {
  content: "Yours";
}

.wrong-questions .answers-container span.correct {
  color: #0dcc0d;
}

.wrong-questions .answers-container span.correct:after {
  content: "Correct";
  right: -52px;
}

.wrong-questions button {
  display: inline-block;
  margin-top: 10px;
  padding: 3px 10px;
  background-color: transparent;
  border: none;
  outline: none;
  border-radius: 5px;
  color: #d21d0f;
  cursor: pointer;
  font-size: 1.05em;
  font-family: 'Bad Script', cursive;
  transition: box-shadow .2s ease;
}

.wrong-questions button:hover {
  box-shadow: 4px 6px 15px 1px #ddd;
}

/* Media queries */

@media screen and (max-width: 661px) {
  .question {
    width: 100vw;
    border-radius: 0;
  }
  
  .status {
    width: 100vw;
    border-radius: 0;
  }
  
  .result {
    width: 85vw;
  }
  
  .copy {
    font-size: 8px;
  }
}

@media screen and (min-width: 662px) {
  .question {
    width: 85vw;
    border-radius: 3px;
  }
  
  .status {
    width: 85vw;
    border-radius: 3px;
  }
  
  .result {
    width: 65vw;
  }
  
  .copy {
    font-size: 11px;
  }
}

.copy {
  color: #fff;
  position: absolute;
  bottom: 3px;
  left: 10px;
  font-weight: 100;
}

.copy a {
  color: #fff;
  text-decoration: none;
  font-weight: bold;
}
</style>