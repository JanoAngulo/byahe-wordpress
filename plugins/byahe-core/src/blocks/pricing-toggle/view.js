/**
 * Shared store for the billing toggle and the pricing cards.
 * Server state (isYearly, status, messages) comes from
 * Byahe\Core\pricing_interactivity_state().
 */
import { store, getContext, getElement } from '@wordpress/interactivity';

const { state } = store( 'byahe/pricing', {
	state: {
		get price() {
			const ctx = getContext();
			return state.isYearly ? ctx.yearly : ctx.monthly;
		},
		get period() {
			const ctx = getContext();
			return state.isYearly ? ctx.periodYearly : ctx.periodMonthly;
		},
	},
	actions: {
		toggle() {
			state.isYearly = ! state.isYearly;
			state.status = state.isYearly
				? state.messages.yearly
				: state.messages.monthly;
		},
	},
	callbacks: {
		reveal() {
			getElement().ref.hidden = false;
		},
	},
} );
