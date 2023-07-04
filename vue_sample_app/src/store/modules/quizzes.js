import { _axios } from "../../axios"
import { router } from "../../router";

export const quizzes = {
    namespaced: true,
    state: () => ({
        quizzes: sessionStorage.getItem('userQuizzes') ? JSON.parse(sessionStorage.getItem('userQuizzes')) : [],
        quiz: {}
    }),
    getters: {
        getQuizzes: (state) => state.quizzes,
        getQuiz: (state) => state.quiz
    },
    actions: {
        fetchQuizzes: async ({ commit }) => {
            try {
                const { data } = await _axios.get('quiz')
                commit('setQuizzes', data)
            } catch (error) {
                console.log(error);
            }
        },
        storeNewQuiz: async ({ commit }, newQuizName) => {
            try {
                const { data } = await _axios.post('quiz', { new_quiz_name: newQuizName })
                commit('setNewQuiz', data)
                router.push({ name: 'QuizEdit', params: { id: data?.id } })
            } catch (error) {
                console.log(error.response.data?.errors);
            }
        },
        updateQuiz: async ({ commit }, updatedQuiz) => {
            try {
                const { data } = await _axios.put(`quiz/${updatedQuiz.id}`, updatedQuiz)
                commit('setUpdatedQuiz', data)
            } catch (error) {
                console.log(error);
            }
        },
        deleteQuiz: async ({ commit }, deletedQuizId) => {
            try {
                const { data } = await _axios.delete(`quiz/${deletedQuizId}`)
                console.log(data);
                commit('setDeletedQuiz', deletedQuizId)
                router.push({ name: 'MyQuizzes' })
            } catch (error) {
                console.log(error);
            }
        }
    },
    mutations: {
        setQuizzes: (state, data) => {
            state.quizzes = data
            sessionStorage.setItem('userQuizzes', JSON.stringify(data))
        },
        setNewQuiz: (state, data) => {
            state.quizzes.push(data)
            sessionStorage.setItem('userQuizzes', JSON.stringify(state.quizzes))
        },
        setQuiz: (state, quizId) => {
            state.quiz = state.quizzes.find(quiz => quiz.id == quizId)
        },
        setUpdatedQuiz: (state, data) => {
            state.quiz = data
            let index = state.quizzes.findIndex(quiz => quiz.id == data.id)
            state.quizzes.splice(index, 1, data)
            sessionStorage.setItem('userQuizzes', JSON.stringify(state.quizzes))
        },
        setDeletedQuiz: (state, deletedQuizId) => {
            let index = state.quizzes.findIndex(quiz => quiz.id == deletedQuizId)
            state.quizzes.splice(index, 1)
            sessionStorage.setItem('userQuizzes', JSON.stringify(state.quizzes))
        }
    },
}