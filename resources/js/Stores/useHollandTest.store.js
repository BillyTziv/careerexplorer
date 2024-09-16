import { defineStore } from 'pinia';

export const useHollandTestStore = defineStore({
	id: 'hollandTest',
	state: () => ({
        id: null,
		questions: [],
        questionIndex: 1,
		status: 'not-started', 
        progress: 0,
        isPreparingTest: false,
		answers: [
            { label: 'Πάρα πολύ', value: 5 },
            { label: 'Αρκετά', value: 4 },
            { label: 'Δεν ξέρω', value: 3 },
            { label: 'Λίγο', value: 2 },
            { label: 'Καθόλου', value: 1 }
        ],
        user: {
            firstname: '',
            lastname: '',
            email: '',
            phone: ''
        },
        sessionRequest: []
	}),
	getters: {
        getTestStatus() {
            return this.status;
        },
        getNextQuestion() {
            //this.questionIndex++;
            return this.questions[this.questionIndex];
        },
        getPreviousQuestion() {
            this.questionIndex--;
            return this.questions[this.questionIndex];
        },
        getProgress() {
            let calculatedProgress = (this.questionIndex / this.questions.length) * 100;

            return parseInt(calculatedProgress.toFixed(0));
        },
        getTestAnswers() {
            return this.questions;
        },
        totalQuestions() {
            return this.questions.length;
        },
        getSubmitPayload( ) {
            return {
                user: this.user,
                answers: this.questions,
                sessionRequest: this.sessionRequest,
                id: this.id
            }
        }
	},
	actions: {
        async startTest() {
            // Randomize questions
            this.isPreparingTest = true;

            this.status = 'in-progress';
        },
        setQuestions( test ) {
            this.id = test.id;

            // Randomize questions and set a new structure .
            this.questions = test.questions
                .map(question => ({
                    
                    id: question.id,
                    title: question.title,
                    description: question.description || 'Description for question',
                    selectedAnswer: null,
                    answers: [
                        { label: 'Πάρα πολύ', value: 5 },
                        { label: 'Αρκετά', value: 4 },
                        { label: 'Δεν ξέρω', value: 3 },
                        { label: 'Λίγο', value: 2 },
                        { label: 'Καθόλου', value: 1 }
                    ]
                }))
                .sort(() => Math.random() - 0.5);
        },
        submitQuestion( answer ) {
            if( this.questionIndex === this.questions.length - 1 ) {
                this.status = 'completed';
                return;
            }

            this.questions[this.questionIndex].selectedAnswer = answer;
            this.questionIndex++;
        },
        setUserData( formData ) {
            this.user = formData;
            this.status = 'ready-to-submit';
        },
        setSessionRequest( value ) {
            this.sessionRequest = value;
        }
	},
});