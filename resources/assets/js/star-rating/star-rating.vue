<template v-cloak>
    <div :class="['vue-star-rating', {'vue-star-rating-rtl':rtl}, {'vue-star-rating-inline': inline}]">
        <div @mouseleave="resetRating" class="vue-star-rating">
            <span v-for="n in maxRating" :class="[{'vue-star-rating-pointer': !readOnly }, 'vue-star-rating-star']">
              <star :fill="fillLevel[n-1]" :size="starSize" :star-id="n" :step="step" :active-color="activeColor"
                    :inactive-color="inactiveColor" :border-color="borderColor" :border-width="borderWidth"
                    :padding="padding" @star-selected="setRating($event, true)" @star-mouse-move="setRating"
                    :rtl="rtl"></star>
            </span>
            <span v-if="showRating" :class="['vue-star-rating-rating-text', textClass]"> {{formattedRating}}</span>
        </div>
    </div>
</template>


<script type="text/javascript">
	import star from './star.vue'

	export default {
		components: {
			star
		},

		model: {
			prop: 'rating',
			event: 'rating-selected'
		},
		props: {
			increment: {
				type: Number,
				default: 1
			},
			rating: {
				type: Number,
				default: 0
			},
			roundStartRating: {
				type: Boolean,
				default: true
			},
			activeColor: {
				type: String,
				default: '#ffd055'
			},
			inactiveColor: {
				type: String,
				default: '#d8d8d8'
			},

			recipeid: {
				type: Number,
				default: 'NULL'
			},
			maxRating: {
				type: Number,
				default: 5
			},
			starSize: {
				type: Number,
				default: 50
			},
			userid: {
				type: Number,
				default: 0
			},
			showRating: {
				type: Boolean,
				default: true
			},
			readOnly: {
				type: Boolean,
				default: false
			},
			textClass: {
				type: String,
				default: ''
			},
			inline: {
				type: Boolean,
				default: false
			},
			borderColor: {
				type: String,
				default: '#999'
			},
			borderWidth: {
				type: Number,
				default: 0
			},
			padding: {
				type: Number,
				default: 0
			},
			rtl: {
				type: Boolean,
				default: false
			},
			fixedPoints: {
				type: Number,
				default: null
			}
		},
		created() {
			this.step = this.increment * 100;
			this.currentRating = this.rating;
			this.selectedRating = this.currentRating;
			this.createStars(this.roundStartRating);
			this.recipe_id = this.recipeid;
			if(this.rating == null){
			    this.rating = 0;
            }
		},

		methods: {
			setRating($event, persist) {
				if(!this.readOnly) {
					const position = (this.rtl) ? (100 - $event.position) / 100 : $event.position / 100;
					this.currentRating = (($event.id + position) - 1).toFixed(2);
					this.currentRating = (this.currentRating > this.maxRating) ? this.maxRating : this.currentRating;
					this.createStars();
					if(persist) {
						this.selectedRating = this.currentRating;
						this.$emit('rating-selected', this.selectedRating);
						this.ratingSelected = true;

						// Traitement du choix

						axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
						var self = this;
						axios.post('/api/note', {
							recette: this.recipe_id,
							note: this.selectedRating,
							userid: this.userid,
						}).then(function(response) {
							self.currentRating = response.data;
						}).catch(function(error) {
							if(error.response) {
								console.log(error.response.data);
								console.log(error.response.status);
								console.log(error.response.headers);
							} else if(error.request) {
								console.log(error.request);
							} else {
								console.log('Error', error.message);
							}
							console.log(error.config);
						});

						this.$emit('rating-selected', this.selectedRating);
					} else {
						this.$emit('current-rating', this.currentRating)
					}
				}
			},
			resetRating() {
				if(!this.readOnly) {
					this.currentRating = this.selectedRating;
					this.createStars(this.shouldRound)
				}
			},
			createStars(round = true) {
				if(round) {
					this.round()
				}
				for(var i = 0; i < this.maxRating; i++) {
					let level = 0;
					if(i < this.currentRating) {
						level = (this.currentRating - i > 1) ? 100 : (this.currentRating - i) * 100
					}
					this.$set(this.fillLevel, i, Math.round(level))
				}
			},
			round() {
				var inv = 1.0 / this.increment;
				this.currentRating = Math.min(this.maxRating, Math.ceil(this.currentRating * inv) / inv)
			}
		},
		computed: {
			formattedRating() {
				return (this.fixedPoints === null) ? this.currentRating : this.currentRating.toFixed(this.fixedPoints)
			},
			shouldRound() {
				return this.ratingSelected || this.roundStartRating
			}
		},
		watch: {
			rating(val) {
				this.currentRating = val;
				this.selectedRating = val;
				this.createStars();
			}
		},
		data() {
			return {
				step: 0,
				fillLevel: [],
				currentRating: 0,
				selectedRating: 0,
				ratingSelected: false,
				recipe_id: "",
			}
		}
	}
</script>

<style scoped>
    .vue-star-rating-star {
        display: inline-block;

    }

    .vue-star-rating-pointer {
        cursor: pointer;
    }

    .vue-star-rating {
        display: flex;
        align-items: center;
    }

    .vue-star-rating-inline {
        display: inline-flex;
    }

    .vue-star-rating-rating-text {
        margin-top: 0px;
        margin-left: 7px;
        position: absolute;
        right: 3%;
        display: none;
    }

    .vue-star-rating-rtl {
        direction: rtl;
    }

    .vue-star-rating-rtl .vue-star-rating-rating-text {
        margin-right: 10px;
        direction: rtl;
    }
</style>
